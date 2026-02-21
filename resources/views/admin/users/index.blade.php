<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
        <div class="text-sm text-gray-500">
            <span class="font-bold text-gray-700">{{ \App\Models\User::count() }}</span> total users
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4 justify-between">
            <div class="relative w-full md:w-96">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <div class="flex gap-2">
                <select name="role" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-600 bg-white">
                    <option {{ request('role') == 'All Roles' ? 'selected' : '' }}>All Roles</option>
                    <option {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option {{ request('role') == 'User' ? 'selected' : '' }}>User</option>
                </select>
                <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-600 bg-white">
                    <option {{ request('status') == 'All Status' ? 'selected' : '' }}>All Status</option>
                    <option {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option {{ request('status') == 'Banned' ? 'selected' : '' }}>Banned</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold text-right">Balance</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold">Joined</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random" class="w-8 h-8 rounded-full">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-lg">Admin</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-lg">User</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                            NPR {{ number_format($user->balance, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->is_banned)
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Banned
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Active
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-3 items-center">
                            @if($user->role !== 'admin')
                            <a href="{{ route('admin.users.login', $user) }}" class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded hover:bg-indigo-100 transition whitespace-nowrap" target="_blank">
                                Login as User
                            </a>
                            @endif
                            
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-500 hover:text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>

                            @if($user->role !== 'admin')
                            <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="{{ $user->is_banned ? 'text-green-600 hover:text-green-800' : 'text-red-500 hover:text-red-700' }}" title="{{ $user->is_banned ? 'Unban' : 'Ban' }}">
                                    @if($user->is_banned)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    @endif
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
