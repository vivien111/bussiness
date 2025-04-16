<?php

namespace App\Filament\Resources\NewsletterScheduleResource\Pages;

use App\Filament\Resources\NewsletterScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsletterSchedule extends EditRecord
{
    protected static string $resource = NewsletterScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
