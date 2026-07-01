<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Kelola Peran</h2>
                <p class="mt-1 text-sm text-slate-500">Lihat dan kelola peran sistem.</p>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <span class="material-symbols-outlined text-base me-1.5" style="line-height:1;">add_circle</span>
                Buat Peran
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 flex items-start gap-3">
                <span class="material-symbols-outlined text-xl text-emerald-600 flex-shrink-0 mt-0.5" style="line-height:1;">check_circle</span>
                <p>{{ $message }}</p>
            </div>
        @endif

        @if ($message = Session::get('danger'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 flex items-start gap-3">
                <span class="material-symbols-outlined text-xl text-red-600 flex-shrink-0 mt-0.5" style="line-height:1;">error</span>
                <p>{{ $message }}</p>
            </div>
        @endif

        @if ($message = Session::get('warning'))
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800 flex items-start gap-3">
                <span class="material-symbols-outlined text-xl text-amber-600 flex-shrink-0 mt-0.5" style="line-height:1;">warning</span>
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Peran</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Pengguna</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Tipe</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($roles as $role)
                            @php
                                $isCoreRole = in_array($role->name, ['Super Admin', 'Event Organizer', 'Customer', 'Gate Scanner']);
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    <div class="flex items-center gap-2">
                                        {{ $role->name }}
                                        @if ($isCoreRole)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-violet-50 text-violet-700">
                                                Inti
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 font-medium text-sm">
                                        {{ $role->users()->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($isCoreRole)
                                        <x-badge color="gray">Sistem</x-badge>
                                    @else
                                        <x-badge color="green">Kustom</x-badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if (!$isCoreRole)
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 font-medium rounded-lg hover:bg-amber-100 transition text-sm">
                                            <span class="material-symbols-outlined text-base me-1" style="line-height:1;">edit</span>
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 font-medium rounded-lg hover:bg-red-100 transition text-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus peran ini?')">
                                                <span class="material-symbols-outlined text-base me-1" style="line-height:1;">delete</span>
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">Peran Sistem</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">Tidak ada peran ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($roles->hasPages())
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>