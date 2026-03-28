<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets()->latest()->paginate(10);
        return view('user.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('user.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = auth()->user()->tickets()->create([
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        $ticketMessage = $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => false,
        ]);

        // ==========================================
        // Trigger n8n Ticket Webhook 
        // ==========================================
        try {
            $webhookUrl = env('N8N_WEBHOOK_URL', 'http://n8n-automation:5678/webhook/smm-events');
            \Illuminate\Support\Facades\Http::timeout(3)->post($webhookUrl, [
                'event_type' => 'ticket_create',
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? '',
                'user_email' => auth()->user()->email ?? '',
                'subject' => $ticket->subject,
                'priority' => $ticket->priority,
                'message' => $request->message,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("n8n ticket webhook failed: " . $e->getMessage());
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load('messages.user');
        return view('user.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['message' => 'required|string']);

        $ticketMessage = $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => false,
        ]);

        $ticket->touch(); // Updated updated_at

        // ==========================================
        // Trigger n8n Ticket Reply Webhook 
        // ==========================================
        try {
            $webhookUrl = env('N8N_WEBHOOK_URL', 'http://n8n-automation:5678/webhook/smm-events');
            \Illuminate\Support\Facades\Http::timeout(3)->post($webhookUrl, [
                'event_type' => 'ticket_reply',
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? '',
                'user_email' => auth()->user()->email ?? '',
                'subject' => $ticket->subject,
                'message' => $request->message,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("n8n ticket reply webhook failed: " . $e->getMessage());
        }

        return back()->with('success', 'Reply sent.');
    }
}