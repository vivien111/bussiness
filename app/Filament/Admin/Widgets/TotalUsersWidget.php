<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;

use App\Models\User;

class TotalUsersWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Utilisateurs', User::count())
                ->description('Nombre total des utilisateurs inscrits')
                ->icon('heroicon-o-user-group')
                ->color('success'),
        ];
    }
}