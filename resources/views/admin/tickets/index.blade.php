<x-admin-layout>
    <x-slot name="header">Support Tickets</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Support Tickets</h2>
        <div class="flex bg-gray-100 p-1 rounded-lg">
            <a href="{{ route('admin.tickets.index') }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ !request('status') ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">All</a>
            <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'open' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Open</a>
            <a href="{{ route('admin.tickets.index', ['status' => 'closed']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'closed' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Closed</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Subject</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold text-center">Priority</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold">Last Update</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-gray-600 font-bold">#{{ $ticket->id }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $ticket->subject }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $ticket->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                    {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                      ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                    {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 
                                      ($ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $tickets->links() }}
        </div>
    </div>
</x-admin-layout>
