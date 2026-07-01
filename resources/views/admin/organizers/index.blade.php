<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Verifikasi Organizer</h2>
                <p class="mt-1 text-sm text-slate-500">Tinjau profil penyelenggara dan setujui untuk memperbolehkan mereka membuat event.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="flex gap-3">
            <a href="{{ route('admin.organizers.index', ['status' => 'pending']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $status === 'pending' ? 'bg-slate-800 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-900' }}">
                Menunggu Verifikasi
            </a>
            <a href="{{ route('admin.organizers.index', ['status' => 'verified']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $status === 'verified' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-900' }}">
                Terverifikasi
            </a>
            <a href="{{ route('admin.organizers.index', ['status' => 'rejected']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-900' }}">
                Ditolak
            </a>
        </div>



        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Organizer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Detail Perusahaan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Rekening Bank</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Dokumen</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($profiles as $profile)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $profile->user->name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $profile->user->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <p class="font-bold text-slate-900">{{ $profile->company_name }}</p>
                                    <div class="mt-1 space-y-0.5 text-xs text-slate-600">
                                        <p><span class="font-semibold text-slate-500">PIC:</span> {{ $profile->pic_name ?: '-' }}</p>
                                        <p><span class="font-semibold text-slate-500">Telp:</span> {{ $profile->phone_number ?: '-' }}</p>
                                    </div>
                                    @if($profile->website_url)
                                        <div class="mt-2">
                                            <a href="{{ $profile->website_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                                <span class="material-symbols-outlined text-[14px]">link</span> Website
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <div class="space-y-0.5">
                                        <p><span class="font-semibold text-slate-500">Bank:</span> {{ $profile->bank_name }}</p>
                                        <p><span class="font-semibold text-slate-500">No:</span> {{ $profile->account_number }}</p>
                                        <p><span class="font-semibold text-slate-500">A/N:</span> {{ $profile->account_holder }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($profile->legal_document_path)
                                        <a href="{{ Storage::url($profile->legal_document_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-medium text-xs transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat Dokumen
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-rose-500 bg-rose-50 px-2 py-1 rounded-md">
                                            <span class="material-symbols-outlined text-[14px]">error</span> Tidak ada dokumen
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($profile->verification_status === 'pending' || $profile->verification_status === 'rejected')
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.organizers.verify', $profile) }}" method="POST" class="inline-block" onsubmit="return confirm('Setujui profil organizer ini?');">
                                                @csrf
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                                    Setujui
                                                </button>
                                            </form>
                                            @if($profile->verification_status === 'pending')
                                                <form action="{{ route('admin.organizers.verify', $profile) }}" method="POST" class="inline-block" onsubmit="return confirm('Tolak profil organizer ini?');">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-rose-700 transition focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                                        Tolak
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400 font-medium italic">Telah diverifikasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                        <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                                        <p class="text-sm font-medium">Tidak ada data organizer dengan status {{ $status }}.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($profiles->hasPages())
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    {{ $profiles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
