<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    // protected static ?string $navigationGroup = 'Gestion des pays';
    protected static ?string $navigationLabel = 'Les Pays';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Champs pour le nom, code_iso2 et code_iso3
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nom du Pays'),

                Forms\Components\TextInput::make('code_iso2')
                    ->required()
                    ->maxLength(2) // Limiter à 2 caractères pour ISO2
                    ->label('Code ISO2'),

                Forms\Components\TextInput::make('code_iso3')
                    ->required()
                    ->maxLength(3) // Limiter à 3 caractères pour ISO3
                    ->label('Code ISO3'),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Nom'),
            Tables\Columns\TextColumn::make('code_iso2')->label('Code ISO2'),
            Tables\Columns\TextColumn::make('code_iso3')->label('Code ISO3'),
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
