<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;

class EventResource extends Resource
{
    protected static function getCitiesByProvince(?string $province): array
    {
        $cities = [
            'Aceh' => ['Banda Aceh', 'Langsa', 'Lhokseumawe', 'Sabang', 'Subulussalam'],
            'Sumatera Utara' => ['Medan', 'Binjai', 'Padang Sidempuan', 'Pematangsiantar', 'Sibolga', 'Tanjungbalai', 'Tebing Tinggi'],
            'Sumatera Barat' => ['Padang', 'Bukittinggi', 'Padangpanjang', 'Payakumbuh', 'Sawahlunto', 'Solok', 'Pariaman'],
            'Riau' => ['Pekanbaru', 'Dumai', 'Bengkalis', 'Pelalawan', 'Siak'],
            'Jambi' => ['Jambi', 'Sungai Penuh'],
            'Sumatera Selatan' => ['Palembang', 'Lubuklinggau', 'Pagar Alam', 'Prabumulih'],
            'Bengkulu' => ['Bengkulu'],
            'Lampung' => ['Bandar Lampung', 'Metro'],
            'DKI Jakarta' => ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur', 'Kepulauan Seribu'],
            'Jawa Barat' => ['Bandung', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya', 'Banjar'],
            'Jawa Tengah' => ['Semarang', 'Magelang', 'Pekalongan', 'Purwokerto', 'Salatiga', 'Surakarta', 'Tegal'],
            'DI Yogyakarta' => ['Yogyakarta', 'Bantul', 'Sleman', 'Kulon Progo', 'Gunung Kidul'],
            'Jawa Timur' => ['Surabaya', 'Malang', 'Madiun', 'Kediri', 'Mojokerto', 'Pasuruan', 'Probolinggo', 'Blitar', 'Batu'],
            'Banten' => ['Serang', 'Cilegon', 'Tangerang', 'Tangerang Selatan', 'South Tangerang'],
            'Bali' => ['Denpasar', 'Badung', 'Bangli', 'Buleleng', 'Gianyar', 'Jembrana', 'Karangasem', 'Klungkung', 'Tabanan'],
            'Nusa Tenggara Barat' => ['Mataram', 'Bima'],
            'Nusa Tenggara Timur' => ['Kupang'],
            'Kalimantan Barat' => ['Pontianak', 'Singkawang'],
            'Kalimantan Tengah' => ['Palangka Raya'],
            'Kalimantan Selatan' => ['Banjarmasin', 'Banjarbaru'],
            'Kalimantan Timur' => ['Samarinda', 'Balikpapan', 'Bontang'],
            'Kalimantan Utara' => ['Tarakan'],
            'Sulawesi Utara' => ['Manado', 'Bitung', 'Kotamobagu', 'Tomohon'],
            'Sulawesi Tengah' => ['Palu'],
            'Sulawesi Selatan' => ['Makassar', 'Palopo', 'Parepare'],
            'Sulawesi Tenggara' => ['Kendari', 'Baubau'],
            'Gorontalo' => ['Gorontalo'],
            'Sulawesi Barat' => ['Mamuju'],
            'Maluku' => ['Ambon', 'Tual'],
            'Maluku Utara' => ['Ternate', 'Tidore Kepulauan'],
            'Papua Barat' => ['Manokwari', 'Sorong'],
            'Papua' => ['Jayapura'],
        ];

        if (!$province || !isset($cities[$province])) {
            return [];
        }

        return array_combine($cities[$province], $cities[$province]);
    }
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Manajemen Event';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Event';
    protected static ?string $pluralModelLabel = 'Data Event';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Event')
                    ->schema([
                        Forms\Components\Select::make('event_category_id')
                            ->relationship('eventCategory', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Kategori Event')
                            ->reactive(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Judul Event'),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Slug URL')
                            ->helperText('Akan dibuat otomatis dari judul event'),
                    ])->columns(2),

                Forms\Components\Section::make('Deskripsi Event')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->label('Deskripsi Lengkap')
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                        Forms\Components\Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->helperText('Maksimal 160 karakter untuk SEO')
                            ->maxLength(160),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('events')
                            ->imageEditor()
                            ->imagePreviewHeight('250')
                            ->maxSize(2048)
                            ->label('Gambar Event'),
                    ])->columns(1),

                Forms\Components\Section::make('Lokasi Event')
                    ->schema([
                        Forms\Components\Select::make('province')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Provinsi')
                            ->options([
                                'Aceh' => 'Aceh',
                                'Sumatera Utara' => 'Sumatera Utara',
                                'Sumatera Barat' => 'Sumatera Barat',
                                'Riau' => 'Riau',
                                'Jambi' => 'Jambi',
                                'Sumatera Selatan' => 'Sumatera Selatan',
                                'Bengkulu' => 'Bengkulu',
                                'Lampung' => 'Lampung',
                                'Kepulauan Bangka Belitung' => 'Kepulauan Bangka Belitung',
                                'Kepulauan Riau' => 'Kepulauan Riau',
                                'DKI Jakarta' => 'DKI Jakarta',
                                'Jawa Barat' => 'Jawa Barat',
                                'Jawa Tengah' => 'Jawa Tengah',
                                'DI Yogyakarta' => 'DI Yogyakarta',
                                'Jawa Timur' => 'Jawa Timur',
                                'Banten' => 'Banten',
                                'Bali' => 'Bali',
                                'Nusa Tenggara Barat' => 'Nusa Tenggara Barat',
                                'Nusa Tenggara Timur' => 'Nusa Tenggara Timur',
                                'Kalimantan Barat' => 'Kalimantan Barat',
                                'Kalimantan Tengah' => 'Kalimantan Tengah',
                                'Kalimantan Selatan' => 'Kalimantan Selatan',
                                'Kalimantan Timur' => 'Kalimantan Timur',
                                'Kalimantan Utara' => 'Kalimantan Utara',
                                'Sulawesi Utara' => 'Sulawesi Utara',
                                'Sulawesi Tengah' => 'Sulawesi Tengah',
                                'Sulawesi Selatan' => 'Sulawesi Selatan',
                                'Sulawesi Tenggara' => 'Sulawesi Tenggara',
                                'Gorontalo' => 'Gorontalo',
                                'Sulawesi Barat' => 'Sulawesi Barat',
                                'Maluku' => 'Maluku',
                                'Maluku Utara' => 'Maluku Utara',
                                'Papua Barat' => 'Papua Barat',
                                'Papua' => 'Papua',
                            ]),
                        Forms\Components\Select::make('city')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Kota')
                            ->options(fn (Forms\Get $get) => static::getCitiesByProvince($get('province'))),
                        Forms\Components\TextInput::make('venue')
                            ->required()
                            ->maxLength(255)
                            ->label('Tempat/Venue'),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255)
                            ->label('Alamat Lengkap'),
                    ])->columns(2),

                Forms\Components\Section::make('Waktu Pelaksanaan')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required()
                            ->label('Tanggal Mulai'),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->required()
                            ->label('Tanggal Selesai')
                            ->after('start_date'),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Jam Mulai'),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Jam Selesai')
                            ->after('start_time'),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Pendaftaran & Pembayaran')
                    ->schema([
                        Forms\Components\Toggle::make('is_free')
                            ->label('Event Gratis')
                            ->helperText('Jika diaktifkan, harga akan otomatis 0')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('price', 0);
                                    $set('price_gold', 0);
                                    $set('price_platinum', 0);
                                }
                            }),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Event (Regular)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->visible(function (Get $get) {
                                if (!$get('event_category_id')) return true;
                                $categoryName = \App\Models\EventCategory::find($get('event_category_id'))?->name;
                                return !($categoryName && str_contains(strtolower($categoryName), 'expo'));
                            })
                            ->disabled(fn (Get $get) => $get('is_free')),
                        Forms\Components\TextInput::make('price_gold')
                            ->label('Harga Gold Package')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->visible(function (Get $get) {
                                if (!$get('event_category_id')) return false;
                                $categoryName = \App\Models\EventCategory::find($get('event_category_id'))?->name;
                                return $categoryName && str_contains(strtolower($categoryName), 'expo');
                            })
                            ->disabled(fn (Get $get) => $get('is_free')),
                        Forms\Components\TextInput::make('price_platinum')
                            ->label('Harga Platinum Package')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->visible(function (Get $get) {
                                if (!$get('event_category_id')) return false;
                                $categoryName = \App\Models\EventCategory::find($get('event_category_id'))?->name;
                                return $categoryName && str_contains(strtolower($categoryName), 'expo');
                            })
                            ->disabled(fn (Get $get) => $get('is_free')),
                        
                        // Down Payment Configuration
                        Forms\Components\Toggle::make('has_down_payment')
                            ->label('Menggunakan Sistem DP')
                            ->helperText('Aktifkan jika event menggunakan sistem pembayaran down payment')
                            ->reactive()
                            ->hidden(fn (Get $get) => $get('is_free')),
                        Forms\Components\Select::make('down_payment_type')
                            ->label('Tipe Down Payment')
                            ->options([
                                'percentage' => 'Persentase',
                                'amount' => 'Nominal Tetap'
                            ])
                            ->default('percentage')
                            ->reactive()
                            ->visible(fn (Get $get) => $get('has_down_payment') && !$get('is_free')),
                        Forms\Components\TextInput::make('down_payment_percentage')
                            ->label('Persentase DP (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(1)
                            ->maxValue(99)
                            ->default(50)
                            ->required()
                            ->visible(fn (Get $get) => $get('has_down_payment') && $get('down_payment_type') === 'percentage' && !$get('is_free')),
                        Forms\Components\TextInput::make('down_payment_amount')
                            ->label('Nominal DP Tetap')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->visible(fn (Get $get) => $get('has_down_payment') && $get('down_payment_type') === 'amount' && !$get('is_free')),
                        Forms\Components\TextInput::make('max_participants')
                            ->label('Maksimal Peserta')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('current_participants')
                            ->label('Peserta Saat Ini')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, $record) {
                                if ($record) {
                                    $component->state($record->actual_participants);
                                }
                            }),
                        Forms\Components\Select::make('payment_methods')
                            ->label('Metode Pembayaran yang Diterima')
                            ->multiple()
                            ->options([
                                'transfer' => 'Transfer Bank',
                                'credit_card' => 'Kartu Kredit',
                                'e-wallet' => 'E-Wallet'
                            ])
                            ->required()
                            ->hidden(fn (Get $get) => $get('is_free')),
                        Forms\Components\Textarea::make('payment_instructions')
                            ->label('Instruksi Pembayaran')
                            ->rows(3)
                            ->hidden(fn (Get $get) => $get('is_free')),
                    ])->columns(2),

                Forms\Components\Section::make('Status Event')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->required()
                            ->label('Featured Event')
                            ->helperText('Event akan ditampilkan di halaman utama'),
                        Forms\Components\Toggle::make('is_trending')
                            ->required()
                            ->label('Trending Event')
                            ->helperText('Event akan ditampilkan di bagian trending'),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->label('Event Aktif')
                            ->helperText('Event dapat diakses oleh pengguna'),
                        Forms\Components\TagsInput::make('tags')
                            ->label('Tags')
                            ->helperText('Tekan enter untuk menambahkan tag'),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email Kontak'),
                        Forms\Components\TextInput::make('contact_phone')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->regex('/^08[0-9]{9,11}$/')
                            ->label('No. Telepon')
                            ->helperText('Format: 08xxxxxxxxxx'),
                        Forms\Components\TextInput::make('organizer_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Penyelenggara'),
                        Forms\Components\TextInput::make('pembicara')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Pembicara')
                            ->visible(function (Get $get) {
                                if (!$get('event_category_id')) return false;
                                $categoryName = \App\Models\EventCategory::find($get('event_category_id'))?->name;
                                return $categoryName && str_contains(strtolower($categoryName), 'expo');
                            }),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Tambahan')
                    ->schema([
                        Forms\Components\RichEditor::make('requirements')
                            ->label('Persyaratan')
                            ->toolbarButtons(['bold', 'bulletList', 'italic']),
                        Forms\Components\RichEditor::make('benefits')
                            ->label('Manfaat/Fasilitas')
                            ->toolbarButtons(['bold', 'bulletList', 'italic']),
                        Forms\Components\Textarea::make('schedule_temp')
                            ->label('Jadwal/Rundown')
                            ->placeholder('Masukkan jadwal, satu per baris')
                            ->rows(5)
                            ->helperText('Tulis setiap item agenda dalam baris baru')
                            ->afterStateHydrated(function (Forms\Components\Textarea $component, $record) {
                                if ($record && $record->schedule && is_array($record->schedule)) {
                                    $component->state(implode("\n", $record->schedule));
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $lines = array_filter(explode("\n", $state));
                                    $schedule = array_map('trim', $lines);
                                    $set('schedule', $schedule);
                                } else {
                                    $set('schedule', null);
                                }
                            })
                            ->dehydrated(false),
                        Forms\Components\Hidden::make('schedule'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('eventCategory.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('province')
                    ->label('Provinsi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->description(fn (?Event $record): string => $record && $record->is_free ? 'Event Gratis' : '')
                    ->color(fn (?Event $record): string => $record && $record->is_free ? 'success' : 'primary')
                    ->visible(function (?Event $record): bool {
                        if (!$record || !$record->eventCategory) return true;
                        return !str_contains(strtolower($record->eventCategory->name), 'expo');
                    }),
                Tables\Columns\TextColumn::make('price_gold')
                    ->label('Harga Gold')
                    ->money('IDR')
                    ->sortable()
                    ->color('warning')
                    ->visible(function (?Event $record): bool {
                        if (!$record || !$record->eventCategory) return false;
                        return str_contains(strtolower($record->eventCategory->name), 'expo');
                    }),
                Tables\Columns\TextColumn::make('price_platinum')
                    ->label('Harga Platinum')
                    ->money('IDR')
                    ->sortable()
                    ->color('primary')
                    ->visible(function (?Event $record): bool {
                        if (!$record || !$record->eventCategory) return false;
                        return str_contains(strtolower($record->eventCategory->name), 'expo');
                    }),
                Tables\Columns\IconColumn::make('has_down_payment')
                    ->label('Sistem DP')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('secondary')
                    ->visible(fn (?Event $record): bool => !$record || !$record->is_free),
                Tables\Columns\TextColumn::make('actual_participants')
                    ->label('Pendaftar')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->getStateUsing(fn (Event $record): int => $record->actual_participants),
                Tables\Columns\TextColumn::make('max_participants')
                    ->label('Maks. Peserta')
                    ->numeric()
                    ->sortable()
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->numeric(1)
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured')
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('warning'),
                Tables\Columns\IconColumn::make('is_trending')
                    ->boolean()
                    ->label('Trending')
                    ->trueIcon('heroicon-o-fire')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('danger'),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event_category_id')
                    ->relationship('eventCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Kategori'),
                Tables\Filters\SelectFilter::make('province')
                    ->options([
                        'Aceh' => 'Aceh',
                        'Sumatera Utara' => 'Sumatera Utara',
                        'Sumatera Barat' => 'Sumatera Barat',
                        'Riau' => 'Riau',
                        'Jambi' => 'Jambi',
                        'Sumatera Selatan' => 'Sumatera Selatan',
                        'Bengkulu' => 'Bengkulu',
                        'Lampung' => 'Lampung',
                        'Kepulauan Bangka Belitung' => 'Kepulauan Bangka Belitung',
                        'Kepulauan Riau' => 'Kepulauan Riau',
                        'DKI Jakarta' => 'DKI Jakarta',
                        'Jawa Barat' => 'Jawa Barat',
                        'Jawa Tengah' => 'Jawa Tengah',
                        'DI Yogyakarta' => 'DI Yogyakarta',
                        'Jawa Timur' => 'Jawa Timur',
                        'Banten' => 'Banten',
                        'Bali' => 'Bali',
                        'Nusa Tenggara Barat' => 'Nusa Tenggara Barat',
                        'Nusa Tenggara Timur' => 'Nusa Tenggara Timur',
                        'Kalimantan Barat' => 'Kalimantan Barat',
                        'Kalimantan Tengah' => 'Kalimantan Tengah',
                        'Kalimantan Selatan' => 'Kalimantan Selatan',
                        'Kalimantan Timur' => 'Kalimantan Timur',
                        'Kalimantan Utara' => 'Kalimantan Utara',
                        'Sulawesi Utara' => 'Sulawesi Utara',
                        'Sulawesi Tengah' => 'Sulawesi Tengah',
                        'Sulawesi Selatan' => 'Sulawesi Selatan',
                        'Sulawesi Tenggara' => 'Sulawesi Tenggara',
                        'Gorontalo' => 'Gorontalo',
                        'Sulawesi Barat' => 'Sulawesi Barat',
                        'Maluku' => 'Maluku',
                        'Maluku Utara' => 'Maluku Utara',
                        'Papua Barat' => 'Papua Barat',
                        'Papua' => 'Papua',
                    ])
                    ->searchable()
                    ->label('Provinsi'),
                Tables\Filters\SelectFilter::make('city')
                    ->options(function (Tables\Filters\SelectFilter $filter): array {
                        return static::getCitiesByProvince(session('selected_province'));
                    })
                    ->searchable()
                    ->label('Kota'),
                Tables\Filters\Filter::make('province_handler')
                    ->hidden()
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['province'])) {
                            session(['selected_province' => $data['province']]);
                        }
                        return $query;
                    }),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Event')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak')
                    ->placeholder('Semua'),
                Tables\Filters\TernaryFilter::make('is_trending')
                    ->label('Trending Event')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak')
                    ->placeholder('Semua'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Event')
                    ->trueLabel('Aktif')
                    ->falseLabel('Non-aktif')
                    ->placeholder('Semua'),
                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Event Gratis')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak')
                    ->placeholder('Semua'),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->columns(2)
                    ->columnSpan(2)
                    ->label('Rentang Tanggal')
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('start_date', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('start_date', '<=', $data['until']));
                    }),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
