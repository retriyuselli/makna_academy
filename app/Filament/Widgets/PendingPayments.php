<?php

namespace App\Filament\Widgets;

use App\Models\EventRegistration;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class PendingPayments extends BaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                EventRegistration::query()
                    ->whereIn('payment_status', [
                        EventRegistration::PAYMENT_STATUS_PENDING,
                        EventRegistration::PAYMENT_STATUS_WAITING_VERIFICATION,
                    ])
                    ->with(['event', 'user'])
                    ->latest('registration_date')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Invoice')->copyable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('event.title')->label('Event')->sortable(),
                Tables\Columns\TextColumn::make('payment_amount')->label('Amount')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('payment_status')->label('Status')->badge()->colors([
                    'warning' => 'pending',
                    'info' => 'waiting_verification',
                ]),
            ]);
    }
}
