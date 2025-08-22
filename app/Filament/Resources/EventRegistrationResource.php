<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventRegistrationResource\Pages;
use App\Filament\Resources\EventRegistrationResource\RelationManagers;
use App\Models\EventRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Event Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                if ($get('event_id')) {
                                    $event = \App\Models\Event::find($get('event_id'));
                                    if ($event) {
                                        $set('payment_amount', $event->price);
                                        // Set payment status berdasarkan is_free
                                        if ($event->is_free) {
                                            $set('payment_status', 'free');
                                        } else {
                                            $set('payment_status', 'pending');
                                        }
                                    }
                                }
                            }),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->required()
                                    ->email()
                                    ->unique('users', 'email')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('company')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('position')
                            ->maxLength(255),
                        Forms\Components\Select::make('experience_level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('motivation')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('special_needs')
                            ->label('Harapan Khusus')
                            ->maxLength(255)
                            ->helperText('Diisi jika ada harapan khusus dari peserta')
                            ->rows(2),
                    ]),

                Forms\Components\Section::make('Attendance & Certificate')
                    ->schema([
                        Forms\Components\Toggle::make('is_attended')
                            ->label('Kehadiran')
                            ->helperText('Centang jika peserta hadir di event')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if (!$state) {
                                    $set('completed_at', null);
                                }
                            })
                            ->required(),
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Tanggal Penyelesaian')
                            ->helperText('Tanggal peserta menyelesaikan event')
                            ->required(fn (Forms\Get $get): bool => $get('is_attended'))
                            ->live()
                            ->disabled(fn (Forms\Get $get): bool => !$get('is_attended'))
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                if ($state && $get('is_attended')) {
                                    $set('certificate_status', 'pending');
                                }
                            }),
                        Forms\Components\TextInput::make('certificate_number')
                            ->label('Nomor Sertifikat')
                            ->helperText('Nomor sertifikat akan terisi otomatis saat diterbitkan')
                            ->default(function (?EventRegistration $record) {
                                if ($record && $record->exists && $record->certificate_number) {
                                    return $record->certificate_number;
                                }
                                return null;
                            })
                            ->disabled()
                            ->dehydrated(fn ($state): bool => filled($state)),
                        Forms\Components\DateTimePicker::make('certificate_issued_at')
                            ->label('Tanggal Penerbitan Sertifikat')
                            ->helperText('Tanggal sertifikat diterbitkan')
                            ->disabled(fn (Forms\Get $get): bool => !$get('is_attended') || !$get('completed_at')),
                        Forms\Components\Select::make('certificate_status')
                            ->label('Status Sertifikat')
                            ->options([
                                'pending' => 'Belum Diterbitkan',
                                'issued' => 'Sudah Diterbitkan',
                                'revoked' => 'Dibatalkan'
                            ])
                            ->default('pending')
                            ->disabled(fn (Forms\Get $get): bool => !$get('is_attended') || !$get('completed_at')),
                        // Forms\Components\FileUpload::make('certificate_file')
                        //     ->label('File Sertifikat')
                        //     ->helperText('Upload file sertifikat dalam format PDF')
                        //     ->directory('certificates')
                        //     ->visibility('private')
                        //     ->acceptedFileTypes(['application/pdf'])
                        //     ->maxSize(5120)
                        //     ->downloadable()
                        //     ->preserveFilenames()
                        //     ->disabled(fn (Forms\Get $get): bool => !$get('is_attended') || !$get('completed_at')),
                    ])->columns(2),

                Forms\Components\Section::make('Registration Details')
                    ->schema([
                        Forms\Components\Select::make('registration_status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Menunggu Pembayaran',
                                'waiting_verification' => 'Menunggu Verifikasi',
                                'down_payment_paid' => 'DP Terbayar',
                                'fully_paid' => 'Lunas',
                                'failed' => 'Gagal',
                                'free' => 'Gratis',
                            ])
                            ->required()
                            ->default('pending')
                            ->reactive()
                            ->disabled(fn (Forms\Get $get) => $get('payment_status') === 'free'),
                        Forms\Components\Select::make('payment_method')
                            ->options(\App\Models\EventRegistration::getPaymentMethods()),
                        Forms\Components\TextInput::make('payment_amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0.00)
                            ->disabled()
                            ->dehydrated()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                if ($get('event_id')) {
                                    $event = \App\Models\Event::find($get('event_id'));
                                    if ($event) {
                                        $set('payment_amount', $event->price);
                                        // Jika event gratis, set payment_status ke free
                                        if ($event->is_free) {
                                            $set('payment_status', 'free');
                                        }
                                    }
                                }
                            })
                            ->reactive(),
                        Forms\Components\DateTimePicker::make('registration_date')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('confirmation_code')
                            ->required()
                            ->maxLength(255)
                            ->default(fn () => 'REG-' . strtoupper(substr(md5(uniqid()), 0, 8)))
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('invoice_number')
                                    ->label('Nomor Invoice')
                                    ->default(fn () => 'INV-' . strtoupper(substr(md5(uniqid()), 0, 8)))
                                    ->disabled()
                                    ->columnSpan(1),
                                Forms\Components\DateTimePicker::make('registration_date')
                                    ->label('Tanggal Pembayaran')
                                    ->columnSpan(1),
                            ])->columns(2),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\FileUpload::make('bukti_pembayaran')
                                    ->label('Bukti Pembayaran')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('payment-proofs')
                                    ->visibility('private')
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->helperText('Maksimal 2MB (JPG, PNG)')
                                    ->imagePreviewHeight('250')
                                    ->loadingIndicatorPosition('left')
                                    ->panelAspectRatio('2:1')
                                    ->panelLayout('integrated')
                                    ->removeUploadedFileButtonPosition('right')
                                    ->uploadProgressIndicatorPosition('left')
                                    ->columnSpan(2),
                            ])->columns(2),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Textarea::make('payment_notes')
                                    ->label('Catatan Pembayaran')
                                    ->rows(3)
                                    ->columnSpan(2),
                            ])->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('confirmation_code')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('registration_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'waiting_verification' => 'warning',
                        'down_payment_paid' => 'info',
                        'fully_paid' => 'success',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'free' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->label('Tanggal Pendaftaran')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->circular()
                    ->defaultImageUrl('https://placehold.co/100x100/png?text=No+Image')
                    ->visibility('private'),
                Tables\Columns\TextColumn::make('payment_notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('registration_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('experience_level')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_attended')
                    ->label('Hadir')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('certificate_number')
                    ->label('No. Sertifikat')
                    ->searchable()
                    ->copyable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('certificate_issued_at')
                    ->label('Tgl. Terbit Sertifikat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->preload()
                    ->multiple()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('registration_status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'waiting_verification' => 'Menunggu Verifikasi',
                        'down_payment_paid' => 'DP Terbayar',
                        'fully_paid' => 'Lunas',
                        'failed' => 'Gagal',
                        'free' => 'Gratis',
                    ]),
                Tables\Filters\Filter::make('registration_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('registration_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('registration_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->form([
                            Forms\Components\Section::make('Detail Pembayaran')
                                ->schema([
                                    Forms\Components\TextInput::make('invoice_number')
                                        ->label('Nomor Invoice')
                                        ->disabled(),
                                    Forms\Components\TextInput::make('payment_amount')
                                        ->label('Total Pembayaran')
                                        ->prefix('Rp')
                                        ->disabled(),
                                    Forms\Components\Select::make('payment_status')
                                        ->label('Status Pembayaran')
                                        ->options([
                                            'pending' => 'Menunggu Pembayaran',
                                            'waiting_verification' => 'Menunggu Verifikasi',
                                            'down_payment_paid' => 'DP Terbayar',
                                            'fully_paid' => 'Lunas',
                                            'failed' => 'Gagal',
                                            'free' => 'Gratis',
                                        ])
                                        ->disabled(),
                                    Forms\Components\Select::make('payment_method')
                                        ->label('Metode Pembayaran')
                                        ->options(\App\Models\EventRegistration::getPaymentMethods())
                                        ->disabled(),
                                    Forms\Components\DateTimePicker::make('payment_date')
                                        ->label('Tanggal Pembayaran')
                                        ->disabled(),
                                ])->columns(2),
                            Forms\Components\Section::make('Bukti & Catatan')
                                ->schema([
                                    Forms\Components\FileUpload::make('bukti_pembayaran')
                                        ->label('Bukti Pembayaran')
                                        ->image()
                                        ->directory('payment-proofs')
                                        ->visibility('private')
                                        ->disabled()
                                        ->columnSpan(2),
                                    Forms\Components\Textarea::make('payment_notes')
                                        ->label('Catatan Pembayaran')
                                        ->disabled()
                                        ->columnSpan(2),
                                ])->columns(2)
                        ]),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('verify_payment')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (EventRegistration $record) {
                            $record->update([
                                'payment_status' => 'fully_paid',
                                'registration_status' => 'confirmed',
                                'payment_date' => now(),
                            ]);

                            // Log the payment verification activity
                            \App\Models\Activity::log(
                                $record->user_id,
                                'payment',
                                "Pembayaran untuk {$record->event->title} telah diverifikasi",
                                $record,
                                [
                                    'status' => 'success',
                                    'event_id' => $record->event_id,
                                    'payment_amount' => $record->payment_amount,
                                    'payment_method' => $record->payment_method
                                ]
                            );
                        })
                        ->visible(fn (EventRegistration $record): bool => 
                            $record->payment_status === 'pending' && $record->bukti_pembayaran),
                    Tables\Actions\Action::make('reject_payment')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Alasan Penolakan')
                                ->required(),
                        ])
                        ->action(function (EventRegistration $record, array $data) {
                            $record->update([
                                'payment_status' => 'failed',
                                'payment_notes' => $data['rejection_reason'],
                            ]);
                        })
                        ->visible(fn (EventRegistration $record): bool => 
                            $record->payment_status === 'pending' && $record->bukti_pembayaran),
                    Tables\Actions\Action::make('download_ticket')
                        ->icon('heroicon-o-ticket')
                        // ->url(fn (EventRegistration $record): string => route('ticket.download', $record))
                        ->openUrlInNewTab()
                        ->visible(fn (EventRegistration $record): bool => 
                            $record->payment_status === 'fully_paid' || $record->payment_status === 'free'),
                    Tables\Actions\Action::make('issue_certificate')
                        ->icon('heroicon-o-document-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Terbitkan Sertifikat')
                        ->modalDescription('Pastikan peserta telah menyelesaikan event sebelum menerbitkan sertifikat.')
                        ->form([
                            Forms\Components\TextInput::make('certificate_number')
                                ->label('Nomor Sertifikat')
                                ->required()
                                ->default(function (EventRegistration $record) {
                                    if ($record->certificate_number) {
                                        return $record->certificate_number;
                                    }
                                    return 'CERT/' . date('Y') . '/' . 
                                           strtoupper(substr($record->event->title ?? 'EVENT', 0, 3)) . '/' . 
                                           str_pad($record->id ?? '0', 5, '0', STR_PAD_LEFT);
                                })
                                ->helperText('Format: CERT/TAHUN/KODE-EVENT/NOMOR-URUT')
                                ->unique(table: 'event_registrations', ignorable: fn ($record) => $record),
                            Forms\Components\DateTimePicker::make('certificate_issued_at')
                                ->label('Tanggal Penerbitan')
                                ->required()
                                ->default(now()),
                            Forms\Components\Hidden::make('is_digital')
                                ->default(true),
                        ])
                        ->action(function (EventRegistration $record, array $data) {
                            if (!$record->isEligibleForCertificate()) {
                                Notification::make()
                                    ->title('Tidak dapat menerbitkan sertifikat')
                                    ->body('Peserta harus hadir dan event harus sudah selesai.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            
                            try {
                                $record->certificate_number = $data['certificate_number'];
                                $record->certificate_issued_at = $data['certificate_issued_at'];
                                $record->certificate_status = 'issued';
                                $record->certificate_metadata = [
                                    'issued_at' => now()->toIso8601String(),
                                    'event_title' => $record->event->title,
                                    'is_digital' => true,
                                    'verification_url' => route('certificate.verify', ['number' => $data['certificate_number']]),
                                    'issuer' => 'System Administrator',
                                    'issuer_position' => 'Event Certificate Administrator'
                                ];
                                $record->save();

                                Notification::make()
                                    ->title('Sertifikat berhasil diterbitkan')
                                    ->body("Nomor Sertifikat: {$record->certificate_number}")
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Gagal menerbitkan sertifikat')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }

                            Notification::make()
                                ->title('Sertifikat berhasil diterbitkan')
                                ->success()
                                ->send();
                        })
                        ->visible(fn (EventRegistration $record): bool => 
                            $record->isEligibleForCertificate() && !$record->hasCertificate()),
                    Tables\Actions\Action::make('view_certificate')
                        ->icon('heroicon-o-document-magnifying-glass')
                        ->color('success')
                        ->url(fn (EventRegistration $record) => route('certificates.show', ['certificate' => $record->certificate_number]))
                        ->openUrlInNewTab()
                        ->visible(fn (EventRegistration $record): bool => $record->hasCertificate()),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->label('Export Selected')
                        ->action(function ($records) {
                            // Add export logic here
                        }),
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
            'index' => Pages\ListEventRegistrations::route('/'),
            'create' => Pages\CreateEventRegistration::route('/create'),
            'edit' => Pages\EditEventRegistration::route('/{record}/edit'),
        ];
    }
}
