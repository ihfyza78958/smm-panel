<x-admin-layout>
    <x-slot name="header">Ticket #{{ $ticket->id }}</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Ticket #{{ $ticket->id }}</h2>
        <a href="{{ route('admin.tickets.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Tickets
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Messages Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Info -->
            <div class="bg-indigo-50 rounded-xl shadow-sm border border-indigo-100 p-5">
                <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $ticket->subject }}</h3>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                    <span>User: <strong>{{ $ticket->user->name }}</strong></span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : ($ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800') }}">{{ ucfirst($ticket->status) }}</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">{{ ucfirst($ticket->priority) }} Priority</span>
                </div>
            </div>

            <!-- Messages -->
            <div class="space-y-4">
                @foreach($ticket->messages as $message)
                    <div class="flex {{ $message->is_admin ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-lg {{ $message->is_admin ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200' }} rounded-xl p-4 shadow-sm">
                            <div class="flex items-center gap-2 mb-1 text-xs {{ $message->is_admin ? 'text-indigo-100' : 'text-gray-500' }}">
                                <span class="font-bold">{{ $message->is_admin ? 'Support Agent' : $message->user->name }}</span>
                                <span>·</span>
                                <span>{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-sm whitespace-pre-wrap">{{ $message->message }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            @if($ticket->status !== 'closed')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reply</label>
                            <textarea name="message" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Type your reply..." required></textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Send Reply</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 rounded-xl shadow-sm border border-gray-100 p-6 text-center text-gray-500">
                    This ticket is closed.
                </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
                @if($ticket->status !== 'closed')
                    <form action="{{ route('admin.tickets.close', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full px-4 py-2 bg-white text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition font-medium text-sm">Mark as Closed</button>
                    </form>
                @else
                     <button disabled class="w-full px-4 py-2 bg-gray-100 text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed font-medium text-sm">Ticket Closed</button>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-2">User Details</h3>
                <div class="text-sm space-y-2">
                    <p class="text-gray-500">Name: <span class="text-gray-900 font-medium">{{ $ticket->user->name }}</span></p>
                    <p class="text-gray-500">Email: <span class="text-gray-900 font-medium">{{ $ticket->user->email }}</span></p>
                    <p class="text-gray-500">Balance: <span class="text-gray-900 font-bold">NPR {{ number_format($ticket->user->balance, 2) }}</span></p>
                    <a href="{{ route('admin.users.edit', $ticket->user) }}" class="inline-block mt-2 text-indigo-600 hover:underline text-sm font-medium">View User Profile &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
