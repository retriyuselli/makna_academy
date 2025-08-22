<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $registration->invoice_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: #2d3748;
            line-height: 1.6;
            padding: 40px;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .company-tagline {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .invoice-header {
            text-align: center;
            margin: 40px 0;
        }
        
        .invoice-title {
            font-size: 42px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }
        
        .invoice-number {
            font-size: 18px;
            color: #718096;
            font-weight: 400;
        }
        
        .content {
            padding: 0 40px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .info-section h3 {
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        
        .info-section p {
            margin-bottom: 8px;
            font-size: 15px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }
        
        .status-down-payment {
            background: #fed7aa;
            color: #c2410c;
        }
        
        .status-fully-paid {
            background: #bbf7d0;
            color: #166534;
        }
        
        .status-pending {
            background: #fecaca;
            color: #dc2626;
        }
        
        .event-card {
            background: #f7fafc;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid #e2e8f0;
        }
        
        .event-title {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 12px;
        }
        
        .event-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 16px;
        }
        
        .event-detail {
            font-size: 14px;
            color: #4a5568;
        }
        
        .event-detail strong {
            color: #2d3748;
            font-weight: 500;
        }
        
        .payment-summary {
            background: #f7fafc;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }
        
        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .payment-row:last-child {
            border-bottom: none;
        }
        
        .payment-label {
            font-weight: 500;
            color: #4a5568;
        }
        
        .payment-value {
            font-weight: 600;
            color: #2d3748;
            font-size: 16px;
        }
        
        .total-row {
            background: #667eea;
            color: white;
            margin: 16px -24px -24px;
            padding: 20px 24px;
            border-radius: 0 0 12px 12px;
        }
        
        .total-row .payment-label,
        .total-row .payment-value {
            color: white;
            font-size: 18px;
            font-weight: 600;
        }
        
        .notes {
            background: #fef5e7;
            border-left: 4px solid #f6ad55;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 32px;
        }
        
        .notes-title {
            font-weight: 600;
            color: #c05621;
            margin-bottom: 12px;
            font-size: 16px;
        }
        
        .notes p {
            color: #744210;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        
        .footer {
            text-align: center;
            padding: 32px 40px;
            background: #f7fafc;
            color: #718096;
            font-size: 13px;
            line-height: 1.8;
        }
        
        .footer p {
            margin-bottom: 4px;
        }
        
        .price {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="company-name">{{ $company->name ?? 'Makna Academy' }}</div>
            <div class="company-tagline">Empowering Growth Through Learning</div>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#{{ $registration->invoice_number }}</div>
        </div>

        <div class="content">
            <!-- Information Grid -->
            <div class="info-grid">
                <div class="info-section">
                    <h3>Bill To</h3>
                    <p><strong>{{ $user->name }}</strong></p>
                    <p>{{ $user->email }}</p>
                    @if($user->phone)
                        <p>{{ $user->phone }}</p>
                    @endif
                    @if($user->company_name)
                        <p>{{ $user->company_name }}</p>
                    @endif
                </div>
                
                <div class="info-section">
                    <h3>Invoice Details</h3>
                    <p><strong>Date:</strong> {{ $registration->created_at->format('d M Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $registration->created_at->addDays(7)->format('d M Y') }}</p>
                    @if($is_down_payment)
                        <p><strong>Type:</strong> Down Payment</p>
                    @endif
                    <div class="status-badge 
                        @if($registration->payment_status === 'down_payment_paid') status-down-payment
                        @elseif($registration->payment_status === 'fully_paid') status-fully-paid
                        @else status-pending
                        @endif">
                        {{ $registration->payment_status_label }}
                    </div>
                </div>
            </div>

            <!-- Event Card -->
            <div class="event-card">
                <div class="event-title">{{ $event->title }}</div>
                <p style="color: #718096; margin-bottom: 16px;">{{ Str::limit($event->description, 150) }}</p>
                
                <div class="event-details">
                    <div class="event-detail">
                        <strong>� Event Date:</strong><br>
                        {{ $event->start_date->format('d M Y') }}
                        @if($event->end_date && $event->end_date != $event->start_date)
                            - {{ $event->end_date->format('d M Y') }}
                        @endif
                    </div>
                    
                    <div class="event-detail">
                        <strong>🕐 Time:</strong><br>
                        {{ $event->start_time ?? 'TBA' }}
                    </div>
                    
                    @if($event->location)
                        <div class="event-detail">
                            <strong>📍 Location:</strong><br>
                            {{ $event->location }}
                        </div>
                    @endif
                    
                    <div class="event-detail">
                        <strong>💰 Event Price:</strong><br>
                        <span class="price">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="payment-summary">
                <div class="payment-row">
                    <span class="payment-label">Event Price</span>
                    <span class="payment-value price">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                </div>
                
                @if($is_down_payment)
                    <div class="payment-row">
                        <span class="payment-label">Down Payment ({{ round(($registration->payment_amount / $event->price) * 100) }}%)</span>
                        <span class="payment-value price">Rp {{ number_format($registration->payment_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Remaining Amount</span>
                        <span class="payment-value price">Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                
                <div class="payment-row total-row">
                    <span class="payment-label">
                        @if($is_down_payment)
                            Total Down Payment
                        @else
                            Total Amount
                        @endif
                    </span>
                    <span class="payment-value price">Rp {{ number_format($registration->payment_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Notes Section -->
            @if($is_down_payment && $registration->payment_status === 'down_payment_paid')
                <div class="notes">
                    <div class="notes-title">📋 Payment Information</div>
                    <p>This is a down payment invoice. The remaining amount of <strong class="price">Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}</strong> must be paid before the event starts.</p>
                    @if($registration->down_payment_date)
                        <p><strong>Down Payment Date:</strong> {{ \Carbon\Carbon::parse($registration->down_payment_date)->format('d M Y H:i') }}</p>
                    @endif
                </div>
            @elseif($registration->payment_status === 'fully_paid')
                <div class="notes">
                    <div class="notes-title">✅ Payment Completed</div>
                    <p>Thank you! Your payment has been completed and verified. You are fully registered for this event.</p>
                </div>
            @elseif($registration->payment_status === 'pending')
                <div class="notes">
                    <div class="notes-title">⏳ Payment Instructions</div>
                    <p>Please complete your payment by transferring the amount above to our bank account. Upload your payment proof through the payment page after transfer.</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ $generated_at->format('d M Y H:i:s') }}</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For questions, contact us at {{ $company->email ?? 'info@maknaacademy.com' }}</p>
        </div>
    </div>
</body>
</html>
