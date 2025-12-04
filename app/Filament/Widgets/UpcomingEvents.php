<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class UpcomingEvents extends BaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(Event::query()->active()->upcoming()->orderBy('start_date')->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Event')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('start_date')->label('Start')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('location')->label('Location')->limit(20),
                Tables\Columns\TextColumn::make('actual_participants')->label('Participants')->sortable(),
                Tables\Columns\TextColumn::make('remaining_slots')->label('Remaining')->state(fn ($record) => $record->getRemainingSlots()),
                Tables\Columns\IconColumn::make('is_free')->label('Free')->boolean(),
            ]);
    }
}
