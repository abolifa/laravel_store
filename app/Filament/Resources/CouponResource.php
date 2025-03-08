<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'ri-coupon-3-line';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                   Forms\Components\Section::make([
                       Forms\Components\TextInput::make('code')
                           ->label('كود الكوبون')
                           ->required()
                           ->maxLength(255),
                       Forms\Components\ToggleButtons::make('type')
                           ->label('نوع الكوبون')
                           ->options([
                               'fixed' => 'ثابت',
                               'percentage' => 'نسبة',
                           ])
                           ->inline()
                           ->grouped()
                           ->default('fixed')
                           ->required(),
                       Forms\Components\TextInput::make('amount')
                           ->label('قيمة الكوبون')
                           ->required()
                           ->numeric()
                           ->default(0.00),
                   ])->maxWidth('4xl'),
                    Forms\Components\Section::make([
                        Forms\Components\ToggleButtons::make('active')
                            ->label('نشط')
                            ->boolean()
                            ->inline()
                            ->grouped()
                            ->default(true)
                            ->required(),
                        Forms\Components\DatePicker::make('expires_at')
                        ->label('تاريخ الانتهاء'),
                        Forms\Components\TextInput::make('usage_limit')
                            ->label('حد الاستخدام')
                            ->required()
                            ->numeric()
                            ->default(1),
                    ])->maxWidth('4xl'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('كود الكوبون')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                ->label('نوع الكوبون')
                ->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('قيمة الكوبون')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('حد الاستخدام')
                    ->sortable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->date()
                    ->sortable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('نشط'),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return 'كوبون';
    }

    public static function getPluralLabel(): ?string
    {
        return 'الكوبونات';
    }
}
