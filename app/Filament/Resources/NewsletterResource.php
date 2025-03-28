<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterResource\Pages;
use App\Filament\Resources\NewsletterResource\RelationManagers;
use App\Models\Newsletter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\DateTimeColumn;
use Filament\Tables\Columns\IDColumn;
use Filament\Tables\Columns\enum;
class NewsletterResource extends Resource
{
    protected static ?string $model = Newsletter::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('country_id')
                    ->label('Pays')
                    ->relationship('country', 'name')
                    ->required(),

                Select::make('state_id')
                    ->label('État/Région')
                    ->relationship('state', 'name')
                    ->required(),

                DateTimePicker::make('scheduled_at')
                    ->label('Date d\'envoi prévue')
                    ->required(),

                Select::make('announcements')
                    ->label('Sélectionner les annonces')
                    ->relationship('announcements', 'title')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                Toggle::make('status')
                    ->label('Activer l\'envoi')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(false),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('country.name')->label('Pays'),
                TextColumn::make('state.name')->label('État/Région'),
                TextColumn::make('scheduled_at')->label('Date d\'envoi'),
                TextColumn::make('status')->label('Statut')->formatStateUsing(fn($state) => $state ? '✅ Activé' : '❌ Désactivé'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('scheduled_at')->label('Programmée pour'),
                
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'scheduled',
                        'success' => 'sent',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'En attente',
                        'scheduled' => 'Programmé',
                        'sent' => 'Envoyé',
                        default => 'Inconnu',
                    }),
            ])
            ->actions([
                Action::make('scheduleNewsletter')
                    ->label('Programmer l’envoi')
                    ->icon('heroicon-o-mail')
                    ->requiresConfirmation()
                    ->action(fn (Newsletter $record) => $record->update([
                        'scheduled_at' => now()->addMinutes(10),
                        'status' => 'scheduled',
                    ]))
                    ->successNotificationTitle('Newsletter programmée avec succès !'),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletters::route('/'),
            'create' => Pages\CreateNewsletter::route('/create'),
            'edit' => Pages\EditNewsletter::route('/{record}/edit'),
        ];
    }
}
