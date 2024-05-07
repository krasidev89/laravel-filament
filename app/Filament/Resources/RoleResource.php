<?php

namespace App\Filament\Resources;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource as ResourcesRoleResource;
use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

class RoleResource extends ResourcesRoleResource
{
    public static function getNavigationSort(): ?int
    {
        return  4;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function table(Table $table): Table
    {
        $parentTable = parent::table($table);

        $parentTable->columns(array_merge($parentTable->getColumns(), [
            Tables\Columns\IconColumn::make('readonly')
                ->label(__('filament-spatie-roles-permissions::filament-spatie.field.readonly'))
                ->boolean()
                ->sortable(),

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
                Tables\Actions\DeleteAction::make(),
            ]),
        ]);

        $parentTable->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])->checkIfRecordIsSelectableUsing(
            fn (Role $role): bool => !$role->readonly
        );

        return $parentTable;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
