<?php

namespace App\Filament\Resources;

use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource as ResourcesPermissionResource;
use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionResource extends ResourcesPermissionResource
{
    public static function getNavigationSort(): ?int
    {
        return  5;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        $parentTable = parent::table($table);

        $parentTable->columns(array_merge($parentTable->getColumns(), [
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('filament-spatie-roles-permissions::filament-spatie.field.created_at'))
                ->dateTime()
                ->sortable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label(__('filament-spatie-roles-permissions::filament-spatie.field.updated_at'))
                ->dateTime()
                ->sortable(),
        ]));

        $parentTable->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]),
        ]);

        $parentTable->bulkActions([
            $parentTable->getBulkAction('Attach to roles'),
        ]);

        return $parentTable;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'view' => Pages\ViewPermission::route('/{record}'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
