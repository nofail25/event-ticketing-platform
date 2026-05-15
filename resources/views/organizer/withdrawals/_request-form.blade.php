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
        <p class="mt-1 text-xs text-gray-500">Available: Rp {{ number_format($wallet['current_balance'], 0, ',', '.') }}</p>
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <x-input-label for="bank_name" value="Bank Name" />
            <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full" value="{{ old('bank_name') }}" required />
            <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="account_number" value="Account Number" />
            <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full" value="{{ old('account_number') }}" required />
            <x-input-error :messages="$errors->get('account_number')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="account_holder" value="Account Holder" />
        <x-text-input id="account_holder" name="account_holder" type="text" class="mt-1 block w-full" value="{{ old('account_holder') }}" required />
        <x-input-error :messages="$errors->get('account_holder')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-3">
        <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'request-withdrawal')">
            Cancel
        </x-secondary-button>
        <x-primary-button>
            Submit Request
        </x-primary-button>
    </div>
</form>
