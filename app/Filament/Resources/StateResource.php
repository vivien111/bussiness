<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'phosphor-map-pin'; // Icône pour l'élément de navigation
    protected static ? string $navigationLabel = 'Les Etats / Villes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('country_id')
                ->label('Pays')
                ->relationship('country', 'name')
                ->required(), // Relation vers le modèle Country

            Forms\Components\TextInput::make('name')
                ->label('Nom de l\'état')
                ->required(),

            Forms\Components\TextInput::make('code')
                ->label('Code de l\'état')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('country.name')->label('Pays'), // Affiche le pays
            Tables\Columns\TextColumn::make('name')->label('Ville'),
            Tables\Columns\TextColumn::make('code')->label('Code Postale'),
        ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
