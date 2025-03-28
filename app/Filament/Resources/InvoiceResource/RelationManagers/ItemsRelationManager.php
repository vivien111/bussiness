<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'product' => 'Produit',
                        'service' => 'Service',
                        'subscription' => 'Abonnement',
                    ])
                    ->required(),
                
                Forms\Components\TextInput::make('item_id')
                    ->numeric()
                    ->required(),
                
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),
                
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('amount')
                    ->money('EUR'),
                
                Tables\Columns\TextColumn::make('quantity'),
                
                Tables\Columns\TextColumn::make('total')
                    ->money('EUR')
                    ->state(fn ($record) => $record->amount * $record->quantity),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}