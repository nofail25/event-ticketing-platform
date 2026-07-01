<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Edit Peran</h2>
                <p class="mt-1 text-sm text-slate-500">Perbarui peran "{{ $role->name }}".</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" :value="__('Nama Peran')" />
                    <x-text-input
                        id="name"
                        class="block mt-1 w-full"
                        type="text"
                        name="name"
                        :value="old('name', $role->name)"
                        placeholder="contoh: Moderator"
                        autofocus
                        required
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    <p class="mt-1 text-sm text-slate-500">Perbarui nama peran kustom ini.</p>
                </div>

                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-xl text-indigo-600 flex-shrink-0 mt-0.5" style="line-height:1;">info</span>
                        <div>
                            <p class="text-sm font-semibold text-indigo-900">Informasi</p>
                            <p class="text-sm text-indigo-800 mt-1">
                                Peran ini saat ini ditetapkan ke <strong>{{ $role->users()->count() }}</strong> pengguna.
                                Mengubah nama akan secara otomatis memperbaruinya untuk semua pengguna yang ditetapkan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <span class="material-symbols-outlined text-base me-2" style="line-height:1;">check_circle</span>
                        Perbarui Peran
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>