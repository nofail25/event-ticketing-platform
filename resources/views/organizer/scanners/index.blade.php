<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Kelola Gate Scanner</h2>
                <p class="mt-1 text-sm text-slate-500">Buat dan kelola akun staf untuk melakukan scan tiket di pintu masuk event Anda.</p>
            </div>
            <a href="{{ route('organizer.scanners.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <span class="material-symbols-outlined text-base me-1.5" style="line-height:1;">add_circle</span>
                Tambah Scanner
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">


        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Dibuat Pada</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($scanners as $scanner)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-900">{{ $scanner->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-500">{{ $scanner->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900 font-medium">{{ $scanner->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                    <form action="{{ route('organizer.scanners.destroy', $scanner) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun scanner ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 font-medium rounded-lg hover:bg-red-100 transition text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-4xl text-indigo-300" style="line-height:1;">qr_code_scanner</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-1">Belum ada Gate Scanner</h3>
                                        <p class="text-slate-500 text-sm mb-6 max-w-sm">Anda belum menambahkan akun staf untuk melakukan pemindaian tiket. Tambahkan sekarang agar mereka bisa bersiap bertugas di hari-H.</p>
                                        <a href="{{ route('organizer.scanners.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-indigo-700 transition">
                                            Buat Akun Scanner
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($scanners->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $scanners->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
