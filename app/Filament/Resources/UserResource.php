<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->description('Informasi dasar pengguna')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255)
                            ->regex('/^08[0-9]{9,11}$/')
                            ->label('Nomor Telepon (format: 08...)'),
                    ])->columns(2),

                Forms\Components\Section::make('Profil & Avatar')
                    ->description('Foto profil dan informasi personal')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar_url')
                            ->image()
                            ->directory('avatars')
                            ->label('Avatar')
                            ->helperText('Upload foto profil (maksimal 1MB)')
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Tanggal Lahir'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Laki-laki',
                                'female' => 'Perempuan',
                            ])
                            ->label('Jenis Kelamin'),
                    ])->columns(2),

                Forms\Components\Section::make('Akun & Keamanan')
                    ->description('Pengaturan akun dan role pengguna')
                    ->schema([
                        Forms\Components\TextInput::make('google_id')
                            ->label('Google ID')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('ID Google untuk OAuth login')
                            ->visibleOn('edit'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn($state) => $state ? \Illuminate\Support\Facades\Hash::make($state) : null)
                            ->label('Password')
                            ->helperText($form->getOperation() === 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : null),
                        Forms\Components\Select::make('role')
                            ->options([
                                'super_admin' => 'Super Admin',
                                'admin' => 'Admin',
                                'customer' => 'Customer',
                            ])
                            ->required()
                            ->label('Role Pengguna'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        // Fallback ke helper function jika avatar_url kosong
                        return user_avatar($record, 64);
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nama'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('Email')
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->label('Telepon')
                    ->placeholder('Tidak ada'),
                Tables\Columns\IconColumn::make('google_id')
                    ->label('Google')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn($record) => !empty($record->google_id))
                    ->tooltip(fn($record) => $record->google_id ? 'Terhubung dengan Google' : 'Belum terhubung Google'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Tanggal Lahir')
                    ->placeholder('Tidak diset'),
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn($state) => match($state) {
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                        default => $state
                    })
                    ->color(fn($state) => match($state) {
                        'male' => 'info',
                        'female' => 'pink',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'super_admin' => 'danger',
                        'admin' => 'warning',
                        'customer' => 'success',
                        default => 'gray'
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'super_admin' => 'Super Admin',
                        'admin' => 'Admin',
                        'customer' => 'Customer',
                        default => $state
                    })
                    ->label('Role'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verifikasi Email')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Terverifikasi' : 'Belum Verifikasi')
                    ->color(fn($state) => $state ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Bergabung')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Update Terakhir')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'admin' => 'Admin',
                        'customer' => 'Customer',
                    ]),
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                    ]),
                Tables\Filters\TernaryFilter::make('google_id')
                    ->label('Google OAuth')
                    ->placeholder('Semua pengguna')
                    ->trueLabel('Terhubung Google')
                    ->falseLabel('Tidak terhubung Google')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('google_id'),
                        false: fn(Builder $query) => $query->whereNull('google_id'),
                    ),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Status Verifikasi Email')
                    ->placeholder('Semua pengguna')
                    ->trueLabel('Email Terverifikasi')
                    ->falseLabel('Email Belum Verifikasi')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn(Builder $query) => $query->whereNull('email_verified_at'),
                    ),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
