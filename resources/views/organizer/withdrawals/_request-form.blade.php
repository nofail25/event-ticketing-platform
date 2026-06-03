<form method="POST" action="{{ route('organizer.withdrawals.store') }}" class="space-y-5">
    @csrf

    <div>
        <x-input-label for="amount" value="Withdrawal Amount" />
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
        <p class="mt-1 text-xs text-slate-500">Available: Rp {{ number_format($wallet['current_balance'], 0, ',', '.') }}</p>
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <x-input-label for="bank_name" value="Bank Name" />
            <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full" value="{{ old('bank_name') }}" required placeholder="e.g., BCA, Mandiri" />
            <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="account_number" value="Account Number" />
            <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full" value="{{ old('account_number') }}" required placeholder="e.g., 1234567890" />
            <x-input-error :messages="$errors->get('account_number')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="account_holder" value="Account Holder" />
        <x-text-input id="account_holder" name="account_holder" type="text" class="mt-1 block w-full" value="{{ old('account_holder') }}" required placeholder="Full name on bank account" />
        <x-input-error :messages="$errors->get('account_holder')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-3 pt-2">
        <button type="button" x-on:click="$dispatch('close-modal', 'request-withdrawal')" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
            Cancel
        </button>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Submit Request
        </button>
    </div>
</form>