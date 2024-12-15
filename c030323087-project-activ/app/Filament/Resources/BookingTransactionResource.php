<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Models\BookingTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Product and Price')
                        ->schema([
                            Forms\Components\Select::make('workshop_id')
                                ->relationship('workshop', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $workshop = Workshop::find($state);
                                    $set('price', $workshop ? $workshop->price : 0);
                                })
                                ->afterStateHydrated(function ($state, callable $get, callable $set) {
                                    $workshop = Workshop::find($state);
                                    $set('price', $workshop ? $workshop->price : 0);
                                }),
                            Forms\Components\TextInput::make('quantity')
                                ->required()
                                ->numeric()
                                ->prefix('Qty People')
                                ->live()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $price = $get('price');
                                    $subTotal = $price * $state;
                                    $totalPpn = $subTotal * 0.11;
                                    $totalAmount = $subTotal + $totalPpn;
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}