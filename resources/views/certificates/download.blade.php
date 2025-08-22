<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $certificate->event->name }} - {{ $certificate->user->name }}</title>
    <link rel="stylesheet" href="{{ public_path('css/certificates/download.css') }}">
    <!-- Tambahan font dan print settings -->
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            @page {
                size: A4 landscape;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Border Pattern -->
        <div class="border-pattern"></div>
        
        <!-- Watermark -->
        <div class="watermark">VERIFIED</div>
        
        <!-- Certificate Content -->
        <div class="certificate">
            <!-- Header -->
            <div class="header">
                <div class="title">SERTIFIKAT</div>
                <div class="subtitle">{{ $certificate->event->category->name }}</div>
            </div>
            
            <!-- Recipient -->
            <div class="recipient">
                <div style="font-size: 24px; margin-bottom: 20px;">Diberikan kepada:</div>
                <div class="name">{{ $certificate->user->name }}</div>
                <div class="event-details">
                    Atas partisipasinya dalam acara<br>
                    <strong style="font-size: 24px; color: #2193b0;">{{ $certificate->event->name }}</strong>
                </div>
                <div class="date">
                    {{ $certificate->certificate_issued_at ? $certificate->certificate_issued_at->isoFormat('D MMMM Y') : now()->isoFormat('D MMMM Y') }}
                </div>
            </div>
            
            <!-- Signatures -->
            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ config('app.name') }}</div>
                    <div class="signature-title">Program Director</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">Event Instructor</div>
                    <div class="signature-title">{{ $certificate->event->name }}</div>
                </div>
            </div>
            
            <!-- Verification Section -->
            <div class="verification-section">
                <div class="qr-wrapper">
                    <img src="data:image/png;base64,{{ $qrCode }}" 
                         class="qr-code" 
                         alt="Scan untuk verifikasi">
                </div>
                <div class="verification-text">
                    <div style="margin-bottom: 5px; font-weight: bold;">Informasi Sertifikat:</div>
                    Nomor: {{ $certificate->certificate_number }}<br>
                    Tanggal: {{ $certificate->certificate_issued_at ? $certificate->certificate_issued_at->format('d F Y') : now()->format('d F Y') }}<br>
                    <div style="margin-top: 5px; font-size: 9px; color: #666;">
                        Scan QR code atau kunjungi:<br>
                        {{ route('certificate.verify', ['number' => $certificate->certificate_number]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
