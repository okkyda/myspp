<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Models\Departmen;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->default(fn() => 'TRX' . mt_rand(10000, 99999)),
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->required()
                    ->tel(), // untuk input nomor telepon

                Forms\Components\TextInput::make('payment_status')
                    ->readOnly()
                    ->default('Pending'),
                Forms\Components\Fieldset::make('Departmen')
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->required()
                            ->label('Departmen Name & Semester')
                            ->options(Departmen::query()->get()->mapWithKeys(function ($departmen) {
                                return [
                                    $departmen->id => $departmen->name . ' - Semester ' . $departmen->semester
                                ];
                            })->toArray())
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($departmen = Departmen::find($state)) {
                                    $set('departmen_cost', $departmen->cost);
                                } else {
                                    $set('departmen_cost', null);
                                }
                            }),
                        Forms\Components\TextInput::make('departmen_cost')
                            ->label('Cost')
                            ->disabled(),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telfon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Success' => 'success',
                        'Failed' => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->width(450)
                    ->height(300),

                Tables\Columns\TextColumn::make('departmen.name')
                    ->label('Departmen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('departmen.semester')
                    ->label('Semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('departmen.cost')
                    ->label('Cost')
                    ->sortable(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
