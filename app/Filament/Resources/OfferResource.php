<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferResource\Pages;
use App\Filament\Resources\OfferResource\RelationManagers;
use App\Models\Offer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'uiw-component';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\ToggleButtons::make('type')
                        ->options([
                            'brand' => 'ماركة',
                            'category' => 'قسم',
                        ])
                        ->inline()
                        ->reactive()
                        ->grouped()
                        ->default('brand')
                        ->required(),
                    Forms\Components\Select::make('category_id')
                        ->label('قسم')
                        ->relationship('category', 'name')
                        ->disabled(
                            fn(Forms\Get $get) => $get('type') === 'brand',
                        ),
                    Forms\Components\Select::make('brand_id')
                        ->label('ماركة')
                        ->relationship('brand', 'name')
                        ->disabled(
                            fn(Forms\Get $get) => $get('type') === 'category',
                        ),
                    Forms\Components\TextInput::make('discount')
                        ->label('نسبة الخصم')
                        ->required()
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\FileUpload::make('image')
                        ->label('الصورة')
                        ->directory('offers')
                        ->image()
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة'),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع العرض'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('القسم')
                    ->numeric()
                    ->badge()
                    ->placeholder('غير محدد')
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('الماركة')
                    ->numeric()
                    ->badge()
                    ->placeholder('غير محدد')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->label('نسبة الخصم')
                    ->numeric()
                    ->badge()
                    ->prefix(' % ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التعديل')
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
            'index' => Pages\ListOffers::route('/'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'عرض';
    }

    public static function getPluralLabel(): ?string
    {
        return 'العروض';
    }

}
