<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaxResource\Pages;
use App\Filament\Resources\TaxResource\RelationManagers;
use App\Models\Tax;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static ?string $navigationIcon = 'tabler-moneybag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\ToggleButtons::make('type')
                        ->label('نوع الضريبة')
                        ->options([
                            'fixed' => 'ثابت',
                            'percentage' => 'نسبة',
                        ])
                        ->inline()
                        ->grouped()
                        ->default('fixed')
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->label('قيمة الضريبة')
                        ->required()
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\ToggleButtons::make('active')
                        ->label('نشط')
                        ->boolean()
                        ->inline()
                        ->default(true)
                        ->grouped()
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع الضريبة')
                    ->formatStateUsing(
                        fn($state) => $state == 'fixed' ? 'ثابت' : 'نسبة'
                    ),
                Tables\Columns\TextColumn::make('amount')
                    ->label('قيمة الضريبة')
                    ->money('LYD')
                    ->badge()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('نشط'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الاضافة')
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
            'index' => Pages\ListTaxes::route('/'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'ضريبة';
    }

    public static function getPluralLabel(): ?string
    {
        return 'الضرائب';
    }
}
