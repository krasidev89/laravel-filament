<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-panels::resources/users.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::resources/users.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::resources/users.plural-label');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Grid::make(['default' => 1, 'sm' => 2, 'md' => 3])->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('filament-panels::resources/users.field.name'))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label(__('filament-panels::resources/users.field.email'))
                        ->required()
                        ->email()
                        ->maxLength(255)
                        ->unique(User::class, 'email', fn ($record) => $record),

                    Forms\Components\Select::make('roles')
                        ->label(__('filament-panels::resources/users.field.roles.name'))
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->preload(),
                ]),
                Forms\Components\Grid::make(['default' => 1, 'sm' => 2, 'md' => 3])->schema([
                    Forms\Components\TextInput::make('password')
                        ->label(__('filament-panels::resources/users.field.password'))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->password()
                        ->revealable()
                        ->maxLength(255)
                        ->rule(Password::default()),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label(__('filament-panels::resources/users.field.password_confirmation'))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->password()
                        ->revealable()
                        ->rule('required', fn ($get) => !!$get('password'))
                        ->same('password'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament-panels::resources/users.field.id'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-panels::resources/users.field.name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-panels::resources/users.field.email'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('filament-panels::resources/users.field.roles.name'))
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-panels::resources/users.field.created_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-panels::resources/users.field.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                //Tables\Filters\SelectFilter::make('roles')->attribute('roles.name'),
                Tables\Filters\SelectFilter::make('roles')
                    ->label(__('filament-panels::resources/users.field.roles.name'))
                    ->relationship('roles', 'name'),
            ])
            ->actions([
                /*
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $user): bool => $user->id != auth()->user()->id)
                    ->label(''),
                */

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                    //->visible(fn (User $user): bool => $user->id != auth()->user()->id)
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
