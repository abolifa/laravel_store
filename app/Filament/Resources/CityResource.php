<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'tabler-map';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('latitude')
                        ->label('خط العرض')
                        ->numeric(),
                    Forms\Components\TextInput::make('longitude')
                        ->label('خط الطول')
                        ->numeric(),
                ])->maxWidth('4xl'),
                Forms\Components\Section::make([
                    Forms\Components\ToggleButtons::make('delivery_charge_type')
                        ->label('نوع التوصيل')
                        ->options([
                            'fixed' => 'ثابت',
                            'percentage' => 'نسبة',
                            'per_km' => 'بالكيلومتر',
                        ])
                        ->inline()
                        ->grouped()
                        ->default('fixed')
                        ->required(),
                    Forms\Components\TextInput::make('delivery_charge')
                        ->label('قيمة التوصيل')
                        ->required()
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\ToggleButtons::make('active')
                        ->label('نشط')
                        ->boolean()
                        ->inline()
                        ->grouped()
                        ->default(true)
                        ->required(),
                ])->maxWidth('4xl'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_charge_type'),
                Tables\Columns\TextColumn::make('delivery_charge')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'مدينة';
    }

    public static function getPluralLabel(): ?string
    {
        return 'المدن';
    }
}
