<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkAction;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static ?string $navigationLabel = 'Annonces';

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\Select::make('country_id')
                    ->label('Pays')
                    ->relationship('country', 'name')
                    ->searchable(),

                Forms\Components\Select::make('state_id')
                    ->label('État / Région')
                    ->relationship('state', 'name')
                    ->searchable(),

                Forms\Components\TextInput::make('invoice_id')
                    ->label('Facture ID')
                    ->numeric(),

                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(191),

                Forms\Components\Textarea::make('content')
                    ->label('Contenu')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->directory('announcements'),

                Forms\Components\TextInput::make('link')
                    ->label('Lien')
                    ->maxLength(191),

                Forms\Components\TextInput::make('price')
                    ->label('Prix')
                    ->required()
                    ->numeric()
                    ->default(100.00)
                    ->prefix('€'),

                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'published' => 'Publié',
                        'rejected' => 'Rejeté',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->size(100),
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Prix')
                    ->money()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn($state) => $state === 'active' ? 'success' : 'danger'),
            ])
            ->actions([
                Action::make('toggleStatus')
                    ->label(fn($record) => $record->status === 'published' ? 'Désactiver' : 'Publier')
                    ->color(fn($record) => $record->status === 'published' ? 'danger' : 'success')
                    ->icon(fn($record) => $record->status === 'published' ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->action(fn($record) => $record->update([
                        'status' => $record->status === 'published' ? 'draft' : 'published'
                    ]))
                    ->requiresConfirmation()
                    ->tooltip('Changer le statut entre Publié et Brouillon'),
            
                EditAction::make(), // Modifier
                DeleteAction::make(), // Supprimer
            ])
            
            ->bulkActions([
                BulkAction::make('delete')
                    ->label('Supprimer les annonces sélectionnées')
                    ->action(fn ($records) => $records->each->delete())
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Ajoute ici les relations si besoin
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
