<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stats(Request $request)
    {
        $request->merge([
            'month' => is_string($request->input('month')) ? trim($request->input('month')) : $request->input('month'),
            'year' => is_string($request->input('year')) ? trim($request->input('year')) : $request->input('year'),
            'date' => is_string($request->input('date')) ? trim($request->input('date')) : $request->input('date'),
        ]);

        $validated = $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:2100',
            'date' => 'nullable|date',
        ]);

        $selectedDate = isset($validated['date']) ? Carbon::parse($validated['date']) : today();
        $month = $validated['month'] ?? $selectedDate->month;
        $year = $validated['year'] ?? $selectedDate->year;

        $monthStart = Carbon::create($year, $month)->startOfMonth();
        $monthEnd = Carbon::create($year, $month)->endOfMonth();

        $monthlyTransactions = Transaction::query()
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->get(['id', 'created_at', 'total_price', 'payment_status']);

        $dailyGrouped = $monthlyTransactions->groupBy(
            fn (Transaction $transaction) => $transaction->created_at->toDateString()
        );

        $transactionsByDay = collect(CarbonPeriod::create($monthStart, $monthEnd))
            ->map(function (Carbon $date) use ($dailyGrouped) {
                $key = $date->toDateString();
                $dayItems = $dailyGrouped->get($key, collect());

                return [
                    'date' => $key,
                    'total_transactions' => $dayItems->count(),
                    'paid_income' => (float) $dayItems
                        ->where('payment_status', 'paid')
                        ->sum('total_price'),
                ];
            })
            ->values();

        $selectedDayTransactions = $monthlyTransactions->filter(
            fn (Transaction $transaction) => $transaction->created_at->toDateString() === $selectedDate->toDateString()
        );

        $hourlyGrouped = $selectedDayTransactions->groupBy(
            fn (Transaction $transaction) => $transaction->created_at->format('H')
        );

        $transactionsByHour = collect(range(0, 23))
            ->map(function (int $hour) use ($hourlyGrouped) {
                $key = str_pad((string) $hour, 2, '0', STR_PAD_LEFT);
                $hourItems = $hourlyGrouped->get($key, collect());

                return [
                    'hour' => $key,
                    'label' => "{$key}:00",
                    'total_transactions' => $hourItems->count(),
                    'paid_income' => (float) $hourItems
                        ->where('payment_status', 'paid')
                        ->sum('total_price'),
                ];
            })
            ->values();

        $periodPaidIncome = (float) $monthlyTransactions
            ->where('payment_status', 'paid')
            ->sum('total_price');
        $periodTransactions = $monthlyTransactions->count();

        $transactionsByMonth = collect(range(1, 12))
            ->map(function (int $monthNumber) use ($year) {
                $start = Carbon::create($year, $monthNumber)->startOfMonth();
                $end = Carbon::create($year, $monthNumber)->endOfMonth();
                $monthTransactions = Transaction::query()
                    ->whereBetween('created_at', [$start, $end]);

                return [
                    'month' => $monthNumber,
                    'total_transactions' => $monthTransactions->count(),
                    'paid_income' => (float) (clone $monthTransactions)
                        ->where('payment_status', 'paid')
                        ->sum('total_price'),
                ];
            })
            ->values();

        $recentStart = today()->subDays(13)->startOfDay();
        $recentEnd = today()->endOfDay();
        $recentTransactions = Transaction::query()
            ->whereBetween('created_at', [$recentStart, $recentEnd])
            ->get(['id', 'created_at', 'total_price', 'payment_status']);

        $recentGrouped = $recentTransactions->groupBy(
            fn (Transaction $transaction) => $transaction->created_at->toDateString()
        );

        $transactionsByRecentDays = collect(CarbonPeriod::create($recentStart, $recentEnd))
            ->map(function (Carbon $date) use ($recentGrouped) {
                $key = $date->toDateString();
                $dayItems = $recentGrouped->get($key, collect());

                return [
                    'date' => $key,
                    'total_transactions' => $dayItems->count(),
                    'paid_income' => (float) $dayItems
                        ->where('payment_status', 'paid')
                        ->sum('total_price'),
                ];
            })
            ->values();

        $totalIncome = Transaction::where('payment_status', 'paid')->sum('total_price');
        $transactionsToday = Transaction::whereDate('created_at', today())->count();

        return response()->json([
            'summary' => [
                'total_income' => (float) $totalIncome,
                'transactions_today' => $transactionsToday,
                'total_transactions' => Transaction::count(),
            ],
            'period_summary' => [
                'total_income' => $periodPaidIncome,
                'total_transactions' => $periodTransactions,
            ],
            'selected_day_summary' => [
                'date' => $selectedDate->toDateString(),
                'total_income' => (float) $selectedDayTransactions
                    ->where('payment_status', 'paid')
                    ->sum('total_price'),
                'total_transactions' => $selectedDayTransactions->count(),
            ],
            'filters' => [
                'month' => $month,
                'year' => $year,
                'date' => $selectedDate->toDateString(),
            ],
            'transactions_by_week' => $transactionsByRecentDays,
            'transactions_by_day' => $transactionsByDay,
            'transactions_by_hour' => $transactionsByHour,
            'transactions_by_month' => $transactionsByMonth,
        ]);
    }
}
