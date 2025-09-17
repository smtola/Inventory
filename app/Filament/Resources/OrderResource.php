<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $navigationGroup = 'Sales & Purchases';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
                Forms\Components\Select::make('supplier_id')->relationship('supplier', 'name'),
                Forms\Components\TextInput::make('customer_info')->required(),
                Forms\Components\DatePicker::make('order_date')->required(),
                Forms\Components\TextInput::make('total_amount')->numeric()->required(),
                Forms\Components\Select::make('type')
                    ->options(['purchase' => 'Purchase', 'sale' => 'Sale'])
                    ->required(),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('supplier.name'),
                Tables\Columns\TextColumn::make('customer_info'),
                Tables\Columns\TextColumn::make('order_date')->date(),
                Tables\Columns\TextColumn::make('total_amount')->money('usd', true),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('created_at')->date(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
