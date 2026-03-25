<?php

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public V2 Adapter API — rate limited to 60 requests/minute per IP
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/v2', [ApiController::class, 'handle']);
    Route::get('/v2', [ApiController::class, 'handle']);
});


// n8n Automated Payment Verification Webhook Receiver
Route::post('/n8n/payment-callback', function (\Illuminate\Http\Request $request) {
    // SECURITY: Strictly restrict this endpoint to localhost and local Docker subnet IPs
    $clientIp = $request->ip();
    $allowedPrefixes = ['127.', '172.17.', '172.18.', '172.19.', '172.2', '172.3', '10.', '192.168.'];
    
    $isLocal = false;
    foreach ($allowedPrefixes as $prefix) {
        if (str_starts_with($clientIp, $prefix)) {
            $isLocal = true;
            break;
        }
    }
    
    if (!$isLocal) {
        \Illuminate\Support\Facades\Log::warning("Blocked external attempt to hit n8n webhook from IP: " . $clientIp);
        return response()->json(['error' => 'Forbidden. Internal network only.'], 403);
    }

    if ($request->header('X-N8N-TOKEN') !== env('N8N_SECRET', 'secret123')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $request->validate([
        'transaction_internal_id' => 'required|integer',
        'status' => 'required|in:completed,rejected',
        'message' => 'nullable|string'
    ]);

    $transaction = \App\Models\Transaction::find($request->transaction_internal_id);
    if (!$transaction) {
        return response()->json(['error' => 'Transaction not found'], 404);
    }

    // Only process if it is currently pending review
    if ($transaction->status !== 'review') {
        return response()->json(['message' => 'Transaction already processed']);
    }

    if ($request->status === 'completed') {
        // Approve payment and add balance
        $transaction->update([
            'status' => 'completed',
            'description' => $request->message ?? 'Automatically verified via n8n.'
        ]);

        $user = $transaction->user;
        $user->balance += $transaction->amount;
        $user->save();
        
        \App\Models\ActivityLog::log('topup_approved', "Auto-approved {$transaction->amount} for User #{$user->id} via n8n.");
        
        return response()->json(['success' => true, 'message' => 'Payment approved & balance added']);
    } 
    
    // If rejected
    $transaction->update([
        'status' => 'rejected',
        'description' => $request->message ?? 'Rejected by automated verification.'
    ]);
    
    return response()->json(['success' => true, 'message' => 'Payment rejected']);
});
