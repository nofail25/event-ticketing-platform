<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdrawal;
use App\Services\OrganizerBalanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function __construct(private readonly OrganizerBalanceService $balanceService)
    {
    }

    public function organizerIndex(Request $request): View
    {
        $wallet = $this->balanceService->summaryFor($request->user()->id);

        $withdrawals = Withdrawal::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('organizer.withdrawals.index', compact('wallet', 'withdrawals'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'decimal:0,2'],
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $organizer = User::query()
                ->whereKey($request->user()->id)
                ->lockForUpdate()
                ->firstOrFail();

            $currentBalance = $this->balanceService->withdrawableBalanceFor($organizer->id);
            $amount = (float) $validated['amount'];

            if ($amount > $currentBalance) {
                throw ValidationException::withMessages([
                    'amount' => 'The requested amount exceeds your current withdrawable balance.',
                ]);
            }

            Withdrawal::create([
                'user_id' => $organizer->id,
                'amount' => $amount,
                'status' => 'pending',
                'bank_info' => [
                    'bank_name' => $validated['bank_name'],
                    'account_number' => $validated['account_number'],
                    'account_holder' => $validated['account_holder'],
                ],
            ]);
        });

        return back()->with('status', 'Withdrawal request submitted for admin approval.');
    }

    public function index(): View
    {
        $withdrawals = Withdrawal::query()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function complete(Withdrawal $withdrawal): RedirectResponse
    {
        DB::transaction(function () use ($withdrawal) {
            $lockedWithdrawal = Withdrawal::query()
                ->whereKey($withdrawal->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedWithdrawal->status !== 'pending') {
                throw ValidationException::withMessages([
                    'withdrawal' => 'Only pending withdrawal requests can be marked as paid.',
                ]);
            }

            $lockedWithdrawal->update([
                'status' => 'completed',
            ]);
        });

        return back()->with('status', 'Withdrawal marked as paid.');
    }
}
