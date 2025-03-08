<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Filament\Resources\DriverResource\RelationManagers;
use App\Models\Driver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $navigationIcon = 'healthicons-f-truck-driver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\FileUpload::make('avatar')
                            ->label('الصورة')
                            ->directory('drivers')
                            ->avatar()
                            ->alignCenter(),
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الالكتروني')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255),
                    ]),
                ]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('license')
                            ->label('رخصة القيادة')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('car')
                            ->label('السيارة')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('plate')
                            ->label('اللوحة')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\ToggleButtons::make('active')
                            ->label('نشط')
                            ->boolean()
                            ->default(true)
                            ->inline()
                            ->grouped()
                            ->required(),
                        Forms\Components\ToggleButtons::make('verified')
                            ->label('موثق')
                            ->boolean()
                            ->default(true)
                            ->inline()
                            ->grouped()
                            ->required(),
                        Forms\Components\ToggleButtons::make('available')
                            ->label('متاح')
                            ->boolean()
                            ->default(true)
                            ->inline()
                            ->grouped()
                            ->required(),
                    ])->columns(3),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الالكتروني')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('license')
                    ->label('رخصة القيادة')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('car')
                    ->sortable()
                    ->label('السيارة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plate')
                    ->sortable()
                    ->label('اللوحة')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->sortable()
                    ->label('نشط'),
                Tables\Columns\ToggleColumn::make('verified')
                    ->sortable()
                    ->label('موثق'),
                Tables\Columns\ToggleColumn::make('available')
                    ->sortable()
                    ->label('متاح'),
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
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'سائق';
    }

    public static function getPluralLabel(): ?string
    {
        return 'السائقين';
    }
}
