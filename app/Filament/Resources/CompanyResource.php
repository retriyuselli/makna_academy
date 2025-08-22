<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Organization Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter company name')
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('legal_name', $state);
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->readOnly()
                            ->placeholder('company-slug')
                            ->helperText('URL-friendly version of company name. Will be auto-generated from name.')
                            ->rules(['alpha_dash'])
                            ->unique(ignoreRecord: true)
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $set('slug', \Illuminate\Support\Str::slug($state))
                            ),
                        Forms\Components\TextInput::make('legal_name')
                            ->readOnly()
                            ->maxLength(255)
                            ->placeholder('Legal entity name')
                            ->helperText('Official registered name if different from company name'),
                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('200')
                            ->imageResizeTargetHeight('200')
                            ->directory('company-logos'),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                            ])
                    ])->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\KeyValue::make('social_media')
                            ->keyLabel('Platform')
                            ->valueLabel('URL/Username')
                            ->addActionLabel('Add Social Media')
                            ->columnSpanFull()
                    ])->columns(2),

                Forms\Components\Section::make('Address')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->rows(3),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('province')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('postal_code')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Select::make('country')
                            ->required()
                            ->default('Indonesia')
                            ->searchable()
                            ->options([
                                'Indonesia' => 'Indonesia',
                                'Malaysia' => 'Malaysia',
                                'Singapore' => 'Singapore',
                            ])
                    ]),

                Forms\Components\Section::make('Business Details')
                    ->schema([
                        Forms\Components\TextInput::make('tax_number')
                            ->label('NPWP')
                            ->maxLength(255)
                            ->helperText('Format: XX.XXX.XXX.X-XXX.XXX'),
                        Forms\Components\TextInput::make('business_license')
                            ->label('NIB/SIUP')
                            ->maxLength(255),
                        Forms\Components\Select::make('company_type')
                            ->required()
                            ->options([
                                'PT' => 'Perseroan Terbatas (PT)',
                                'CV' => 'Commanditaire Vennootschap (CV)',
                                'Foundation' => 'Yayasan',
                                'Individual' => 'Perusahaan Perorangan',
                            ]),
                        Forms\Components\DatePicker::make('established_date')
                            ->required()
                            ->maxDate(now()),
                        Forms\Components\TextInput::make('employee_count')
                            ->numeric()
                            ->minValue(1)
                            ->step(1)
                            ->suffix('employees'),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->helperText('Inactive companies will not be displayed in public listings'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Company $record): string => $record->company_type)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Slug copied')
                    ->copyMessageDuration(1500)
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('province')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Status'),
                Tables\Columns\TextColumn::make('employee_count')
                    ->sortable()
                    ->alignEnd()
                    ->label('Employees'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('company_type')
                    ->multiple()
                    ->options([
                        'PT' => 'Perseroan Terbatas (PT)',
                        'CV' => 'Commanditaire Vennootschap (CV)',
                        'Foundation' => 'Yayasan',
                        'Individual' => 'Perusahaan Perorangan',
                    ]),
                Tables\Filters\SelectFilter::make('province')
                    ->multiple()
                    ->options(fn (): array => Company::distinct()->pluck('province', 'province')->toArray()),
                Tables\Filters\SelectFilter::make('city')
                    ->multiple()
                    ->options(fn (): array => Company::distinct()->pluck('city', 'city')->toArray()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Companies')
                    ->trueLabel('Active Companies')
                    ->falseLabel('Inactive Companies')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check'),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-o-x-mark')
                ])
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
