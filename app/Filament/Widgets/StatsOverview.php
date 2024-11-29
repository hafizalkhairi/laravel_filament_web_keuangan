<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $pemasukan = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('categories.is_expense', false)
            ->sum('transactions.amount');

        $pengeluaran = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('categories.is_expense', true)
            ->sum('transactions.amount');
        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($pemasukan, 0, ',', '.')),
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($pengeluaran, 0, ',', '.')),
            Stat::make('Selisih', 'Rp ' .  number_format($pemasukan - $pengeluaran, 0, ',', '.')),
        ];
    }
}
