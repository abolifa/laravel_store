<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages;
use App\Filament\Resources\SectionResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'tabler-star';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('title')
                        ->label('العنوان')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('short_description')
                        ->label('الوصف المختصر')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('categories')
                        ->label('الأقسام')
                        ->options(Category::all()->pluck('name', 'id'))
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->reactive(), // Reacts when changed
                    Forms\Components\Select::make('product_type')
                        ->label('نوع المنتج')
                        ->reactive()
                        ->options([
                            'all_products' => 'كل المنتجات',
                            'new_arrivals' => 'الجديدة',
                            'top_rated' => 'الأعلى تقييماً',
                            'best_selling' => 'الرائجة',
                            'custom' => 'مخصص',
                        ])
                        ->required(),
                    Forms\Components\Select::make('products')
                        ->label('المنتجات')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->hidden(fn(Forms\Get $get) => $get('product_type') !== 'custom')
                        ->options(
                            fn(Forms\Get $get) => Product::whereHas('categories', function ($query) use ($get) {
                                $query->whereIn('categories.id', $get('categories') ?? []);
                            })->pluck('name', 'id')
                        ),
                ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_description')
                    ->label('الوصف المختصر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_type')
                    ->label('نوع المنتج')
                    ->formatStateUsing(
                        fn($state) => match ($state) {
                            'all_products' => 'كل المنتجات',
                            'new_arrivals' => 'الجديدة',
                            'top_rated' => 'الأعلى تقييماً',
                            'best_selling' => 'الرائجة',
                            'custom' => 'مخصص',
                        }
                    )
                    ->badge(),
                Tables\Columns\TextColumn::make('categories')
                    ->label('الأقسام')
                    ->formatStateUsing(
                        function ($record) {
                            if (is_array($record->categories)) {
                                return Category::whereIn('id', $record->categories)
                                    ->pluck('name')
                                    ->implode(' - ');
                            }
                            return '-';
                        }
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'قسم';
    }

    public static function getPluralLabel(): ?string
    {
        return 'الاقسام';
    }

}
