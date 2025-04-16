<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterScheduleResource\Pages;
use App\Filament\Resources\NewsletterScheduleResource\RelationManagers;
use App\Models\NewsletterSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsletterScheduleResource extends Resource
{
    protected static ?string $model = NewsletterSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('frequency')
                    ->options([
                        'weekly' => 'Hebdomadaire',
                        'monthly' => 'Mensuelle',
                        'bi-monthly' => 'Bi-Mensuelle',
                    ])
                    ->required(),

                Forms\Components\Select::make('day_of_week')
                    ->options([
                        'Monday' => 'Lundi',
                        'Tuesday' => 'Mardi',
                        'Wednesday' => 'Mercredi',
                        'Thursday' => 'Jeudi',
                        'Friday' => 'Vendredi',
                        'Saturday' => 'Samedi',
                        'Sunday' => 'Dimanche',
                    ])
                    ->visible(fn($get) => $get('frequency') === 'weekly')
                    ->required(), // Ajouté pour garantir la sélection

                Forms\Components\Select::make('day_of_month')
                    ->options(range(1, 31))
                    ->visible(fn($get) => in_array($get('frequency'), ['monthly', 'bi-monthly']))
                    ->required(), // Ajouté pour garantir la sélection

                Forms\Components\TimePicker::make('send_time')->required(), // Déjà correct, juste de l'indentation

                Forms\Components\Select::make('state_id')
                    ->label('Département')
                    ->relationship('state', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('frequency')->label('Fréquence'),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Jour de la semaine')
                    ->formatStateUsing(fn($state, $record) =>
                        $record->frequency === 'weekly' ? $state : '-'
                    ),
                Tables\Columns\TextColumn::make('day_of_month')
                    ->label('Jour du mois')
                    ->formatStateUsing(fn($state, $record) =>
                        in_array($record->frequency, ['monthly', 'bi-monthly']) ? $state : '-'
                    ),
                Tables\Columns\TextColumn::make('send_time')->label('Heure d\'envoi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    

    public static function getRelations(): array
    {
        return [
            // Ajoute ici des relations si nécessaire
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSchedules::route('/'),
            'create' => Pages\CreateNewsletterSchedule::route('/create'),
            'edit' => Pages\EditNewsletterSchedule::route('/{record}/edit'),
        ];
    }
}
