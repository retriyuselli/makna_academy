<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Event - {{ $event->title }} - {{ $user->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --text-dark: #2c3e50;
            --text-gray: #7f8c8d;
            --bg-light: #f8f9fa;
            --border-color: #e9ecef;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            color: var(--text-dark);
            line-height: 1.5;
        }

        .ticket-container {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .ticket-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .ticket-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%), 
                        linear-gradient(-45deg, rgba(255,255,255,0.1) 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.1) 75%), 
                        linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.1) 75%);
            background-size: 30px 30px;
            background-position: 0 0, 0 15px, 15px -15px, -15px 0px;
            opacity: 0.3;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .ticket-title {
            font-size: 18px;
            font-weight: 500;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .ticket-body {
            padding: 40px;
        }

        .event-info {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            border-left: 4px solid var(--accent-color);
        }

        .event-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .event-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .detail-icon {
            width: 20px;
            height: 20px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 12px;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .participant-info {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .participant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .qr-section {
            text-align: center;
            padding: 30px;
            background: var(--bg-light);
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .qr-container {
            width: 150px;
            height: 150px;
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .qr-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .registration-number {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
            color: var(--text-dark);
            background: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            display: inline-block;
            margin-top: 10px;
        }

        .ticket-footer {
            background: var(--text-dark);
            color: white;
            padding: 25px 40px;
            text-align: center;
            font-size: 12px;
        }

        .footer-text {
            opacity: 0.8;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--success-color);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 10px;
        }

        .status-badge::before {
            content: '✓';
            font-weight: bold;
        }

        .divider {
            height: 1px;
            background: var(--border-color);
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            background: var(--accent-color);
            border-radius: 50%;
        }

        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .important-note h4 {
            color: #856404;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .important-note ul {
            color: #856404;
            font-size: 12px;
            margin-left: 15px;
        }

        .important-note li {
            margin-bottom: 5px;
        }

        @media print {
            body {
                background: white;
            }
            
            .ticket-container {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <div class="logo">Makna Academy</div>
            <div class="ticket-title">Tiket Peserta Event</div>
        </div>

        <div class="ticket-body">
            <!-- Event Information -->
            <div class="event-info">
                <h1 class="event-title">{{ $event->title }}</h1>
                
                <div class="status-badge">Tiket Valid - Terdaftar</div>
                
                <div class="event-details">
                    <div class="detail-item">
                        <div class="detail-icon">📅</div>
                        <div class="detail-content">
                            <div class="detail-label">Tanggal Event</div>
                            <div class="detail-value">
                                @if($event->start_date)
                                    {{ \Carbon\Carbon::parse($event->start_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    @if($event->end_date && $event->end_date !== $event->start_date)
                                        - {{ \Carbon\Carbon::parse($event->end_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    @endif
                                @else
                                    Tanggal belum ditentukan
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">🕐</div>
                        <div class="detail-content">
                            <div class="detail-label">Waktu</div>
                            <div class="detail-value">
                                @if($event->start_time)
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                    @if($event->end_time)
                                        - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }} WIB
                                    @else
                                        WIB
                                    @endif
                                @else
                                    Waktu belum ditentukan
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">📍</div>
                        <div class="detail-content">
                            <div class="detail-label">Lokasi</div>
                            <div class="detail-value">
                                @if($event->venue)
                                    {{ $event->venue }}<br>
                                @endif
                                {{ $event->location }}
                                @if($event->city), {{ $event->city }}@endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">🎟️</div>
                        <div class="detail-content">
                            <div class="detail-label">Tipe Tiket</div>
                            <div class="detail-value">
                                @if($registration->package_type)
                                    {{ ucfirst(str_replace('_', ' ', $registration->package_type)) }}
                                @else
                                    Standar
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Participant Information -->
            <div class="participant-info">
                <h2 class="section-title">Informasi Peserta</h2>
                
                <div class="participant-grid">
                    <div class="detail-item">
                        <div class="detail-icon">👤</div>
                        <div class="detail-content">
                            <div class="detail-label">Nama Lengkap</div>
                            <div class="detail-value">{{ $registration->name ?: $user->name }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">📧</div>
                        <div class="detail-content">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $registration->email ?: $user->email }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">📱</div>
                        <div class="detail-content">
                            <div class="detail-label">No. Telepon</div>
                            <div class="detail-value">{{ $registration->phone ?: $user->phone ?: '-' }}</div>
                        </div>
                    </div>

                    @if($registration->company)
                    <div class="detail-item">
                        <div class="detail-icon">🏢</div>
                        <div class="detail-content">
                            <div class="detail-label">Perusahaan</div>
                            <div class="detail-value">{{ $registration->company }}</div>
                        </div>
                    </div>
                    @endif

                    @if($registration->position)
                    <div class="detail-item">
                        <div class="detail-icon">💼</div>
                        <div class="detail-content">
                            <div class="detail-label">Posisi</div>
                            <div class="detail-value">{{ $registration->position }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="detail-item">
                        <div class="detail-icon">💳</div>
                        <div class="detail-content">
                            <div class="detail-label">Status Pembayaran</div>
                            <div class="detail-value">{{ $registration->payment_status_label }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- QR Code Section -->
            <div class="qr-section">
                <h3 class="section-title" style="justify-content: center;">Kode Verifikasi</h3>
                
                <div class="qr-container">
                    @php
                        $confirmationCode = $registration->confirmation_code ?: 'REG-' . str_pad($registration->id, 6, '0', STR_PAD_LEFT);
                        $qrData = json_encode([
                            'type' => 'event_ticket',
                            'registration_id' => $registration->id,
                            'confirmation_code' => $confirmationCode,
                            'event_id' => $registration->event_id,
                            'user_id' => $registration->user_id,
                            'verify_url' => url('/verify-ticket/' . $confirmationCode)
                        ]);
                        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=130x130&format=png&error=M&margin=1&data=' . urlencode($qrData);
                    @endphp
                    <img src="{{ $qrCodeUrl }}" 
                         alt="QR Code untuk verifikasi tiket" 
                         onerror="this.parentElement.innerHTML='<div style=\'font-size:10px;color:#666;text-align:center;line-height:1.2;\'>QR Code<br>{{ $confirmationCode }}</div>'" />
                </div>
                
                <div class="registration-number">
                    {{ $confirmationCode }}
                </div>
                
                <p style="font-size: 12px; color: var(--text-gray); margin-top: 10px;">
                    Tunjukkan QR Code atau kode registrasi ini saat check-in di lokasi event
                </p>
            </div>

            <!-- Important Notes -->
            <div class="important-note">
                <h4>Informasi Penting:</h4>
                <ul>
                    <li>Tiket ini adalah bukti resmi pendaftaran Anda pada event {{ $event->title }}</li>
                    <li>Harap tiba 30 menit sebelum acara dimulai untuk registrasi ulang</li>
                    <li>Bawa identitas diri (KTP/SIM/Passport) yang sesuai dengan data registrasi</li>
                    <li>Tiket ini tidak dapat dipindahtangankan tanpa persetujuan penyelenggara</li>
                    @if($event->contact_email || $event->contact_phone)
                        <li>Untuk pertanyaan lebih lanjut, hubungi: 
                            @if($event->contact_email){{ $event->contact_email }}@endif
                            @if($event->contact_phone && $event->contact_email) atau @endif
                            @if($event->contact_phone){{ $event->contact_phone }}@endif
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <div class="footer-text">
                Tiket dicetak pada {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y HH:mm') }} WIB<br>
                © {{ date('Y') }} Makna Academy. Semua hak dilindungi.
            </div>
        </div>
    </div>
</body>
</html>