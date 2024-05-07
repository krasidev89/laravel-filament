<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-panels::resources/products.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::resources/products.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::resources/products.plural-label');
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
                        ->unique(Product::class, 'name', fn ($record) => $record),

                    /*
                    Forms\Components\Select::make('category_id')
                        ->label(__('filament-panels::resources/products.field.category.name'))
                        ->relationship('category', 'name', fn (Builder $query) => $query->where('active', 1))
                        ->required()
                        ->preload()
                        ->visibleOn('create'),

                    Forms\Components\Select::make('category_id')
                        ->label(__('filament-panels::resources/products.field.category.name'))
                        ->relationship('category', 'name')
                        ->required()
                        ->preload()
                        ->visibleOn('edit'),

                    Forms\Components\Select::make('category_id')
                        ->label(__('filament-panels::resources/products.field.category.name'))
                        ->relationship('category', 'name', function (string $operation, Builder $query) {
                            if ($operation === 'create') {
                                $query->where('active', 1);
                            }
                        })
                        ->required()
                        ->preload(),
                    */

                    Forms\Components\Select::make('category_id')
                        ->label(__('filament-panels::resources/products.field.category.name'))
                        ->relationship('category', 'name', fn (Product $product, Builder $query) => $query->where('id', $product->category_id)->orWhere('active', 1))
                        ->required()
                        ->preload(),
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
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament-panels::resources/products.field.id'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-panels::resources/products.field.name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('filament-panels::resources/products.field.category.name'))
                    ->badge()
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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('filament-panels::resources/products.field.category.name'))
                    ->relationship(name: 'category', titleAttribute: 'name'),

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
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
