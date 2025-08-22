<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificate of Completion</title>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        @page {
            margin: 0;
            size: landscape;
        }
        body {
            font-family: 'Poppins', 'Arial', sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            page-break-after: avoid;
        }
        .border-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 15px solid;
            border-image: linear-gradient(45deg, #2193b0, #6dd5ed) 1;
            z-index: 1;
        }
        .certificate {
            position: relative;
            z-index: 2;
            background: #fff;
            padding: 30px;
            height: calc(100% - 40px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 15px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 150px;
            color: rgba(0,0,0,0.03);
            z-index: 1;
            pointer-events: none;
        }
        .title {
            font-size: 42px;
            font-weight: 800;
            color: #2193b0;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 4px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .subtitle {
            font-size: 24px;
            color: #555;
            margin: 10px 0;
            font-style: regular;
        }
        .name {
            font-size: 48px;
            color: #2c3e50;
            margin: 20px 0;
            font-weight: bold;
            text-transform: capitalize;
            border-bottom: 4px solid #2193b0;
            padding-bottom: 5px;
            font-family: 'Poppins', sans-serif;
        }
        .description {
            font-size: 18px;
            color: #555;
            margin: 15px 0;
            line-height: 1.4;
            max-width: 600px;
        }
        .verification-section {
            position: absolute;
            bottom: 50px;
            right: 50px;
            text-align: right;
            font-size: 12px;
            color: #666;
            z-index: 3;
        }
        .qr-code {
            width: 100px;
            height: 100px;
            margin-bottom: 10px;
        }
        .verification-text {
            font-size: 10px;
            color: #888;
            max-width: 200px;
            margin-top: 5px;
        }
        .event-title {
            font-size: 32px;
            color: #2193b0;
            margin: 15px 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
        }
        .certificate-number {
            font-size: 14px;
            color: #666;
            text-align: left;
        }
        .qr-wrapper {
            background: white;
            padding: 8px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 10px;
        }
        .qr-code {
            display: block;
            width: 100px;
            height: 100px;
            image-rendering: pixelated;
            image-rendering: -webkit-optimize-contrast;
        }
        .issued-date {
            font-size: 14px;
            color: #666;
            text-align: right;
        }
        .signature-area {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
            width: 100%;
            max-width: 800px;
        }
        .signature {
            text-align: center;
            min-width: 150px;
            margin: 0 20px;
        }
        .signature-line {
            width: 100%;
            border-bottom: 2px solid #2193b0;
            margin-bottom: 5px;
        }
        .signature-title {
            font-size: 14px;
            color: #555;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .signature-name {
            font-size: 12px;
            color: #666;
        }
        .decorative-line {
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #2193b0, transparent);
            margin: 10px 0;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .certificate-container {
                height: 100vh;
                width: 100vw;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="border-pattern"></div>
        <div class="certificate">
            <div class="watermark">MA</div>
            
            <div class="header">
                <div class="title">Certificate of Achievement</div>
                <div class="decorative-line"></div>
                <div class="subtitle">This is to certify that</div>
            </div>
            
            <div class="name">{{ $certificate->name }}</div>
            
            <div class="description">
                telah berhasil menyelesaikan pelatihan
            </div>

            <!-- Main Certificate Content -->
            <div class="event-details">
                <h2 class="event-title">{{ $certificate->event->title ?? 'Event Title' }}</h2>
                <p class="event-date">
                    @if($certificate->event)
                        {{ $certificate->event->start_date->format('d F Y') }} - {{ $certificate->event->end_date->format('d F Y') }}
                    @else
                        {{ now()->format('d F Y') }}
                    @endif
                </p>
            </div>
            
            <div class="description">
                dengan prestasi yang memuaskan pada tanggal 
                {{ $certificate->completed_at ? $certificate->completed_at->format('d F Y') : now()->format('d F Y') }}
            </div>
            
            <div class="signature-area">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Program Director</div>
                    <div class="signature-name">{{ $certificate->event->organizer_name ?? 'Program Director' }}</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Course Instructor</div>
                    <div class="signature-name">Event Instructor</div>
                </div>
            </div>
            
            <!-- Verification Section -->
            <div class="verification-section">
                <div class="qr-wrapper">
                    <img src="data:image/png;base64,{{ $qrCode }}" 
                         class="qr-code" 
                         alt="Scan untuk verifikasi"
                         style="width: 100px; height: 100px; display: block; margin-bottom: 10px;">
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
            
            <div class="footer">
                <div class="certificate-info">
                    <div class="certificate-number">
                        No. Sertifikat: {{ $certificate->certificate_number }}
                    </div>
                    <div class="certificate-date">
                        Diterbitkan pada: {{ $certificate->certificate_issued_at ? $certificate->certificate_issued_at->format('d F Y') : now()->format('d F Y') }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
