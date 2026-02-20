<x-admin-layout>
    <x-slot name="header">Activity Logs</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Activity Logs</h2>
        <span class="text-sm text-gray-500">Last 50 entries per page</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-32">Time</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Action</th>
                        <th class="px-6 py-4 font-semibold">Subject</th>
                        <th class="px-6 py-4 font-semibold">Details</th>
                        <th class="px-6 py-4 font-semibold">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('M d H:i:s') }}
                            <div class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($log->user)
                                <div class="font-medium text-gray-900">{{ $log->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $log->user->email }}</div>
                            @else
                                <span class="text-gray-400">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $actionColors = [
                                    'order' => 'bg-blue-100 text-blue-800',
                                    'login' => 'bg-green-100 text-green-800',
                                    'payment' => 'bg-yellow-100 text-yellow-800',
                                    'refund' => 'bg-red-100 text-red-800',
                                    'api' => 'bg-purple-100 text-purple-800',
                                    'admin' => 'bg-orange-100 text-orange-800',
                                    'coupon' => 'bg-pink-100 text-pink-800',
                                ];
                                $color = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            @if($log->subject_type && $log->subject_id)
                                <span class="font-mono text-xs">{{ class_basename($log->subject_type) }}#{{ $log->subject_id }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <span class="text-xs text-gray-600 truncate block" title="{{ $log->description }}">{{ Str::limit($log->description, 80) }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400 font-mono">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">No activity logs recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
