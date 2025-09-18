<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MateriResource\Pages;
use App\Filament\Resources\MateriResource\RelationManagers;
use App\Models\Materi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MateriResource extends Resource
{
    protected static ?string $model = Materi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Materi Pembelajaran';

    protected static ?string $modelLabel = 'Materi';

    protected static ?string $pluralModelLabel = 'Materi Pembelajaran';

    protected static ?string $navigationGroup = 'Event Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->description('Informasi dasar tentang materi pembelajaran')
                    ->schema([
                        Forms\Components\Select::make('event_id')
                            ->label('Event')
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Materi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('File Upload')
                    ->description('Upload file materi pembelajaran')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('File Materi')
                            ->disk('public')
                            ->directory('materials')
                            ->openable()
                            ->preserveFilenames()
                            ->maxSize(102400) // 100MB
                            ->acceptedFileTypes([
                                'application/pdf',
                                'video/mp4', 'video/avi', 'video/mov', 'video/wmv',
                                'audio/mp3', 'audio/wav', 'audio/aac',
                                'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml',
                                'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
                                'text/html', 'text/css', 'text/javascript', 'text/php', 'text/python',
                                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/plain', 'text/markdown'
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                    // Set file name
                                    $set('file_name', $state->getClientOriginalName());
                                    
                                    // Set file size (in bytes)
                                    $set('file_size', $state->getSize());
                                    
                                    // Set file extension
                                    $set('file_extension', strtolower($state->getClientOriginalExtension()));
                                    
                                    // Auto-detect file type based on extension
                                    $extension = strtolower($state->getClientOriginalExtension());
                                    $type = match($extension) {
                                        'pdf' => Materi::TYPE_PDF,
                                        'mp4', 'avi', 'mov', 'wmv' => Materi::TYPE_VIDEO,
                                        'mp3', 'wav', 'aac' => Materi::TYPE_AUDIO,
                                        'jpg', 'jpeg', 'png', 'gif', 'svg' => Materi::TYPE_IMAGE,
                                        'doc', 'docx', 'txt', 'md' => Materi::TYPE_DOCUMENT,
                                        'zip', 'rar', '7z' => Materi::TYPE_ARCHIVE,
                                        'html', 'css', 'js', 'php', 'py' => Materi::TYPE_SOURCE_CODE,
                                        'ppt', 'pptx' => Materi::TYPE_PRESENTATION,
                                        default => Materi::TYPE_DOCUMENT
                                    };
                                    $set('type', $type);
                                }
                            })
                            ->columnSpanFull(),
                        
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('file_name')
                                    ->label('Nama File')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Diisi otomatis dari file yang diupload'),
                                
                                Forms\Components\TextInput::make('file_size')
                                    ->label('Ukuran File (bytes)')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Diisi otomatis dari file yang diupload'),
                                
                                Forms\Components\TextInput::make('file_extension')
                                    ->label('Ekstensi')
                                    ->maxLength(10)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Diisi otomatis dari file yang diupload'),
                            ]),
                    ]),

                Section::make('Kategori & Akses')
                    ->description('Pengaturan kategori dan level akses materi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Jenis File')
                                    ->options(Materi::getTypes())
                                    ->required(),
                                
                                Forms\Components\Select::make('category')
                                    ->label('Kategori')
                                    ->options(Materi::getCategories())
                                    ->required()
                                    ->default(Materi::CATEGORY_HANDOUT),
                                
                                Forms\Components\Select::make('access_level')
                                    ->label('Level Akses')
                                    ->options([
                                        Materi::ACCESS_PUBLIC => 'Publik',
                                        Materi::ACCESS_REGISTERED => 'Terdaftar',
                                        Materi::ACCESS_COMPLETED => 'Selesai Event',
                                        Materi::ACCESS_PREMIUM => 'Premium'
                                    ])
                                    ->required()
                                    ->default(Materi::ACCESS_COMPLETED),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),

                Section::make('Pengaturan')
                    ->description('Pengaturan status dan metadata')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                                
                                Forms\Components\DateTimePicker::make('expires_at')
                                    ->label('Kadaluarsa'),
                                
                                Forms\Components\Hidden::make('uploaded_by')
                                    ->default(function() {
                                        return Auth::check() ? Auth::id() : 1;
                                    }),
                                
                                Forms\Components\Hidden::make('download_count')
                                    ->default(0),
                                
                                Forms\Components\Hidden::make('upload_date')
                                    ->default(now()),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Materi')
                    ->searchable()
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Materi::TYPE_PDF => 'danger',
                        Materi::TYPE_VIDEO => 'info',
                        Materi::TYPE_AUDIO => 'warning',
                        Materi::TYPE_IMAGE => 'success',
                        Materi::TYPE_ARCHIVE => 'gray',
                        default => 'primary',
                    }),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('access_level')
                    ->label('Akses')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Materi::ACCESS_PUBLIC => 'success',
                        Materi::ACCESS_REGISTERED => 'info',
                        Materi::ACCESS_COMPLETED => 'warning',
                        Materi::ACCESS_PREMIUM => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('download_count')
                    ->label('Download')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('formatted_file_size')
                    ->label('Ukuran'),
                
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Upload oleh')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('upload_date')
                    ->label('Tanggal Upload')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'title')
                    ->preload(),
                
                SelectFilter::make('type')
                    ->label('Jenis File')
                    ->options(Materi::getTypes()),
                
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(Materi::getCategories()),
                
                SelectFilter::make('access_level')
                    ->label('Level Akses')
                    ->options([
                        Materi::ACCESS_PUBLIC => 'Publik',
                        Materi::ACCESS_REGISTERED => 'Terdaftar',
                        Materi::ACCESS_COMPLETED => 'Selesai Event',
                        Materi::ACCESS_PREMIUM => 'Premium'
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ])
            ->defaultSort('upload_date', 'desc');
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
            'index' => Pages\ListMateris::route('/'),
            'create' => Pages\CreateMateri::route('/create'),
            'edit' => Pages\EditMateri::route('/{record}/edit'),
        ];
    }
}
