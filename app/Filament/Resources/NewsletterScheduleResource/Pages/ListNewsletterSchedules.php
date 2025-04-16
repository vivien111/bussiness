<?php

namespace App\Filament\Resources\NewsletterScheduleResource\Pages;

use App\Filament\Resources\NewsletterScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterSchedules extends ListRecords
{
    protected static string $resource = NewsletterScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
