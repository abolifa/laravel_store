<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'tabler-package';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\RichEditor::make('description')
                        ->label('الوصف')
                        ->columnSpanFull(),
                ]),
                Forms\Components\Section::make([
                    Forms\Components\Select::make('brand_id')
                        ->label('الماركة')
                        ->relationship('brand', 'name')
                        ->required(),
                    Forms\Components\Select::make('categories')
                        ->label('الأقسام')
                        ->relationship('categories', 'name')
                        ->searchable()
                        ->preload()
                        ->multiple(),
                    Forms\Components\FileUpload::make('image')
                        ->label('الصورة الرئيسية')
                        ->image()
                        ->grow(false)
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make([
                    Forms\Components\ToggleButtons::make('stock_type')
                        ->reactive()
                        ->label('نوع المخزون')
                        ->options([
                            'limited' => 'محدود',
                            'unlimited' => 'غير محدود',
                        ])
                        ->inline()
                        ->grouped()
                        ->default('unlimited')
                        ->required(),
                    Forms\Components\ToggleButtons::make('active')
                        ->label('نشط')
                        ->boolean()
                        ->inline()
                        ->grouped()
                        ->default(true)
                        ->required(),
                    Forms\Components\ToggleButtons::make('returnable')
                        ->label('قابل للترجيع')
                        ->boolean()
                        ->inline()
                        ->grouped()
                        ->default(true)
                        ->required(),
                ])->columns(3),

                Forms\Components\Repeater::make('variants')
                    ->label('تنوع المنتج')
                    ->relationship('variants')
                    ->schema([
                        Forms\Components\Select::make('unit_id')
                            ->label('وحدة القياس')
                            ->relationship('unit', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('measurement')
                            ->label('قياس المنتج')
                            ->required(),
                        Forms\Components\ColorPicker::make('color')
                            ->label('اللون'),
                        Forms\Components\TextInput::make('price')
                            ->label('السعر')
                            ->required(),
                        Forms\Components\TextInput::make('discount')
                            ->label('الخصم')
                            ->default(0),
                        Forms\Components\TextInput::make('quantity')
                            ->disabled(
                                fn(callable $get) => $get('../../stock_type') === 'unlimited',
                            )
                            ->required(
                                fn(callable $get) => $get('../../stock_type') === 'limited',
                            )
                            ->label('الكمية'),
                        Forms\Components\FileUpload::make('image')
                            ->label('الصورة')
                            ->directory('products')
                            ->columnSpanFull(),
                    ])->columnSpanFull()
                    ->columns(3)
                    ->minItems(1)
                    ->defaultItems(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة'),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('الماركة')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->label('الأقسام')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),
                Tables\Columns\TextColumn::make('stock_type')
                    ->label('المخزون')
                    ->formatStateUsing(
                        function ($record) {
                            if ($record->stock_type === 'unlimited') {
                                return 'غير محدود';
                            } else {
                                return $record->variants->sum('quantity');
                            }
                        }
                    )
                    ->sortable()
                    ->color(
                        function ($record) {
                            if ($record->stock_type === 'unlimited') {
                                return 'gray';
                            } else {
                                $stock = $record->variants->sum('quantity');
                                if ($stock > 25) {
                                    return 'success';
                                } elseif ($stock > 10) {
                                    return 'warning';
                                } else {
                                    return 'danger';
                                }
                            }
                        }
                    )
                    ->badge(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('نشط'),
                Tables\Columns\ToggleColumn::make('returnable')
                    ->label('قابل للترجيع'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'منتج';
    }

    public static function getPluralLabel(): ?string
    {
        return 'المنتجات';
    }
}
