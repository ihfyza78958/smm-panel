<x-admin-layout>
    <x-slot name="header">Announcements</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Announcements</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Create Form -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">New Announcement</h3>
                <form method="POST" action="{{ route('admin.announcements.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title" required placeholder="Announcement title" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea name="content" rows="4" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Announcement content..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="warning">Warning</option>
                                <option value="danger">Danger</option>
                            </select>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Active</span>
                        </label>
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                            Create Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- List -->
        <div class="lg:col-span-2 space-y-4">
            @forelse($announcements as $announcement)
                @php
                    $typeColors = [
                        'info' => 'border-blue-200 bg-blue-50',
                        'success' => 'border-green-200 bg-green-50',
                        'warning' => 'border-yellow-200 bg-yellow-50',
                        'danger' => 'border-red-200 bg-red-50',
                    ];
                    $color = $typeColors[$announcement->type] ?? 'border-gray-200 bg-gray-50';
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $color }}">{{ ucfirst($announcement->type) }}</span>
                                @if(!$announcement->is_active)
                                    <span class="text-xs text-red-600 font-medium">Inactive</span>
                                @endif
                                <span class="text-xs text-gray-400">{{ $announcement->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <h4 class="font-bold text-gray-900">{{ $announcement->title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $announcement->content }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
                    No announcements yet. Create one using the form.
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
