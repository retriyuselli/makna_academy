<?php

namespace App\Filament\Widgets;

use App\Models\EventRegistration;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class RecentRegistrations extends BaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(EventRegistration::query()->with(['event', 'user'])->latest('registration_date')->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->sortable(),
                Tables\Columns\TextColumn::make('event.title')->label('Event')->sortable(),
                Tables\Columns\TextColumn::make('payment_status')->label('Status')->badge()->colors([
                    'warning' => 'pending',
                    'info' => 'waiting_verification',
                    'success' => 'fully_paid',
                    'danger' => 'failed',
                    'gray' => 'free',
                ]),
                Tables\Columns\TextColumn::make('registration_date')->label('Registered At')->dateTime()->sortable(),
            ]);
    }
}
