<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->required()
                    ->relationship('category', 'name'),
                Forms\Components\DatePicker::make('transaction_date')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('note')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('category.image')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('category.name')
                    ->description(fn (Transaction $record): string => $record->name)
                    ->label('Transaksi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('category.is_expense')
                    ->label('Tipe')
                    ->trueIcon('heroicon-o-arrow-up-circle')
                    ->falseIcon('heroicon-o-arrow-down-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->prefix('Rp ')
                    // ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->color('info'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-trash')
                    ->modalHeading('Hapus Transaksi')
                    ->modalDescription('Yakin Menghapus Data ini?')
                    ->modalCancelActionLabel('Tidak')
                    ->modalSubmitActionLabel('Ya, Yakin'),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
