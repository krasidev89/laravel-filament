<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-m-square-2-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-panels::resources/categories.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::resources/categories.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::resources/categories.plural-label');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('filament-panels::resources/products.field.name'))
                        ->required()
                        ->maxLength(255)
                        ->unique(Category::class, 'name', fn ($record) => $record),
                ]),
                Forms\Components\Grid::make(1)->schema([
                    Forms\Components\Textarea::make('description')
                        ->label(__('filament-panels::resources/products.field.description'))
                        ->rows(10),

                    Forms\Components\Toggle::make('active')
                        ->label(__('filament-panels::resources/products.field.active')),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament-panels::resources/products.field.id'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-panels::resources/products.field.name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('active')
                    ->label(__('filament-panels::resources/products.field.active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-panels::resources/products.field.created_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-panels::resources/products.field.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('active')
                    ->label(__('filament-panels::resources/products.field.active'))
                    ->options([
                        0 => __('filament-panels::resources/products.field.inactive'),
                        1 => __('filament-panels::resources/products.field.active'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->checkIfRecordIsSelectableUsing(
                fn (Category $category): bool => $category->products->isEmpty()
            )
            ->defaultSort('id', 'desc');

        return $table;
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
