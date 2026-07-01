<form method="POST" action="{{ route('organizer.withdrawals.store') }}" class="space-y-5">
    @csrf

    <div>
        <x-input-label for="amount" value="Jumlah Penarikan" />
        <x-text-input
            id="amount"
            name="amount"
            type="number"
            step="0.01"
            min="1"
            max="{{ number_format($wallet['current_balance'], 2, '.', '') }}"
            class="mt-1 block w-full"
            value="{{ old('amount') }}"
            required
        />
        <p class="mt-1 text-xs text-slate-500">Tersedia: Rp {{ number_format($wallet['current_balance'], 0, ',', '.') }}</p>
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>

    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4">
        <p class="font-semibold text-sm">Dana akan ditransfer ke rekening tersimpan Anda:</p>
        <ul class="list-disc list-inside mt-2 text-sm">
            <li><strong>Bank:</strong> {{ $profile->bank_name ?? '-' }}</li>
            <li><strong>No. Rekening:</strong> {{ $profile->account_number ?? '-' }}</li>
            <li><strong>Atas Nama:</strong> {{ $profile->account_holder ?? '-' }}</li>
        </ul>
        <p class="mt-2 text-xs italic">Anda dapat mengubah data rekening bank di halaman profil Anda jika status belum terverifikasi.</p>
    </div>

    <div class="flex items-center justify-end gap-3 pt-2">
        <button type="button" x-on:click="$dispatch('close-modal', 'request-withdrawal')" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
            Batal
        </button>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            <span class="material-symbols-outlined text-base me-2" style="line-height:1;">check_circle</span>
            Kirim Permintaan
        </button>
    </div>
</form>