<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sertifikat {{ $certificate->name ?? $certificate->user->name }} - {{ $certificate->event->title }}</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    
    <style>
      :root {
        --primary-color: #2c3e50;
        --accent-color: #ffffff;
        --text-color: #333;
        --light-bg: #f9f9f9;
      }

      @page {
        size: A4 landscape;
        margin: 0;
      }

      body {
        font-family: "Poppins", sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
        color: var(--text-color);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .certificate-container {
        width: 297mm;
        height: 210mm;
        max-height: 210mm;
        position: relative;
        background: white;
        padding-top: 18mm;    /* Padding atas untuk screen view */
        padding-bottom: 18mm; /* Padding bawah untuk screen view */
        padding-left: 25mm;   /* Padding kiri untuk screen view */
        padding-right: 25mm;  /* Padding kanan untuk screen view */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        box-sizing: border-box;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background-image: radial-gradient(
            circle at 50px 50px,
            var(--accent-color) 0px,
            transparent 1px
          ),
          radial-gradient(
            circle at 150px 150px,
            var(--accent-color) 0px,
            transparent 1px
          );
        background-size: 200px 200px;
        background-position: 0 0, 100px 100px;
        background-color: white;
      }

      .certificate-container::before,
      .certificate-container::after {
        content: "";
        position: absolute;
        width: 100px;
        height: 100px;
        border: 2px solid var(--accent-color);
        pointer-events: none;
        z-index: 1;
      }

      .certificate-container::before {
        top: 15px; /* Adjusted for increased padding */
        left: 15px;
        border-width: 2px 0 0 2px;
      }

      .certificate-container::after {
        bottom: 15px; /* Adjusted for increased padding */
        right: 15px;
        border-width: 0 2px 2px 0;
      }

      .certificate-header {
        margin-bottom: 1px;
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
      }

        .certificate-title {
        color: var(--primary-color);
        font-size: 32px;
        margin: 0 0 5px;
        text-transform: uppercase;
        letter-spacing: 2px;
        line-height: 1.1;
      }

      .certificate-subtitle {
        color: var(--accent-color);
        font-size: 16px;
        margin: 0 0 15px;
        font-weight: 500;
      }

      .certificate-body {
        margin: 0;
        position: relative;
        z-index: 2;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        text-align: center;
      }

      .awarded-to {
        font-size: 15px;
        margin-bottom: 5px;
        font-weight: 600;
        color: #666;
        text-align: center;
        width: 100%;
      }

      .recipient-name {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-color);
        margin: 10px 0 15px;
        padding: 10px 0;
        border-top: 2px solid #eee;
        border-bottom: 2px solid #eee;
        line-height: 1.2;
        text-align: center;
        width: 100%;
      }

      .certificate-text {
        font-size: 13px;
        line-height: 1.8;
        max-width: 700px;
        margin: 0 auto 40px;
        text-align: center;
        color: #666;
      }

      .signature-section {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        width: 100%;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
      }

      .signature {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 10px;
      }

      .signature-line {
        border-top: 1px solid #000;
        width: 200px;
        margin: 0 auto 10px;
      }

      .signature-name {
        font-weight: 600;
        margin-top: 5px;
        margin-inline: auto;
        justify-content: center;
        text-align: center;
        width: 100%;
      }

      .signature-title {
        color: #666;
        font-size: 14px;
        justify-content: center;
        text-align: center;
        width: 100%;
      }

        .certificate-date {
        margin-top: 20px;
        font-style: italic;
        font-size: 10px;
      }

      .certificate-verification {
        margin-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #eee;
      }

      .certificate-number {
        font-size: 9px;
        margin: 0;
        color: #666;
        font-weight: 500;
      }

      .qr-code-section {
        display: flex;
        flex-direction: column;
        align-items: center;
      }

      .qr-code img {
        width: 80px;
        height: 80px;
        border: 1px solid #ddd;
        border-radius: 5px;
      }

      .qr-text {
        font-size: 9px;
        color: #999;
        margin-top: 5px;
        text-align: center;
      }

      .certificate-actions {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        gap: 10px;
      }

      .btn-print {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
        transition: all 0.3s ease;
      }

      .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
      }

      /* Print utilities */
      .print-only {
        display: none;
      }

      .no-print {
        display: block;
      }

      /* Print quality enhancements */
      img {
        max-width: 100%;
        height: auto;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
      }

      /* Ensure consistent spacing */
      .print-spacing {
        margin: 0;
        padding: 0;
      }

      @media print {
        .print-only {
          display: block !important;
        }
        
        .no-print {
          display: none !important;
        }
      }

      .certificate-logo {
        max-width: 100px;
        margin: 0 auto 15px;
        display: block;
      }

      .watermark {
        position: absolute;
        opacity: 0.05;
        font-size: 120px;
        font-weight: bold;
        color: var(--primary-color);
        z-index: 0;
        pointer-events: none;
        white-space: nowrap;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-30deg);
        width: 80%;
        text-align: center;
        letter-spacing: 4px;
        line-height: 1;
        margin: 0;
        padding: 0;
      }

      @media print {
        /* Reset dan optimasi dasar untuk print */
        * {
          -webkit-print-color-adjust: exact !important;
          color-adjust: exact !important;
          print-color-adjust: exact !important;
        }

        body {
          background: white !important;
          margin: 0 !important;
          padding: 0 !important;
          font-family: "Poppins", sans-serif !important;
          color: #333 !important;
          -webkit-font-smoothing: antialiased !important;
          -moz-osx-font-smoothing: grayscale !important;
        }

        /* Page setup untuk A4 landscape */
        @page {
          size: A4 landscape !important;
          margin-top: 15mm !important;    /* Margin atas */
          margin-bottom: 15mm !important; /* Margin bawah */
          margin-left: 20mm !important;   /* Margin kiri */
          margin-right: 20mm !important;  /* Margin kanan - diseimbangkan */
          padding: 0 !important;
          border: none !important;
          box-shadow: none !important;
        }

        .certificate-container {
          width: 100% !important;
          height: calc(210mm - 30mm) !important; /* A4 landscape height minus top+bottom margins */
          max-height: calc(210mm - 30mm) !important;
          margin: 0 !important;
          padding-top: 12mm !important;    /* Padding dalam atas */
          padding-bottom: 12mm !important; /* Padding dalam bawah */
          padding-left: 18mm !important;   /* Padding dalam kiri */
          padding-right: 18mm !important;  /* Padding dalam kanan */
          border: none !important;
          box-shadow: none !important;
          border-radius: 0 !important;
          background: white !important;
          page-break-inside: avoid !important;
          break-inside: avoid !important;
          overflow: hidden !important;
          position: relative !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: flex-start !important; /* Changed from space-between to flex-start */
        }

        /* Sembunyikan elemen yang tidak perlu saat print */
        .certificate-actions {
          display: none !important;
          visibility: hidden !important;
        }

        /* Optimasi header */
        .certificate-header {
          margin-bottom: 8mm !important;
          text-align: center !important;
        }

        .certificate-logo {
          max-width: 60px !important;
          height: auto !important;
          margin-top: 5px !important;
          margin: 0 auto 6px !important;
          display: block !important;
        }

        .certificate-title {
          font-size: 24px !important;
          color: #2c3e50 !important;
          margin: 0 0 6px 0 !important;
          line-height: 1.1 !important;
          font-weight: 700 !important;
        }

        .certificate-subtitle {
          font-size: 12px !important;
          color: #666 !important;
          margin: 0 0 8mm 0 !important;
        }

        /* Optimasi body */
        .certificate-body {
          flex-grow: 1 !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: flex-start !important; /* Changed from center to flex-start */
          text-align: center !important;
          margin: 0 !important;
          padding-top: 5mm !important; /* Add small top padding */
        }

        .awarded-to {
          font-size: 14px !important;
          color: #666 !important;
          margin-bottom: 6px !important;
        }

        .recipient-name {
          font-size: 22px !important;
          color: #2c3e50 !important;
          font-weight: 700 !important;
          margin: 6px 0 10px 0 !important;
          padding: 6px 0 !important;
          border-top: 2px solid #eee !important;
          border-bottom: 2px solid #eee !important;
          line-height: 1.2 !important;
        }

        .certificate-text {
          font-size: 13px !important;
          line-height: 1.4 !important;
          color: #666 !important;
          margin: 0 auto 12px auto !important;
          max-width: 650px !important;
        }

        /* Optimasi signature */
        .signature-section {
          margin-top: 15mm !important; /* Increased margin to push signature down appropriately */
          display: flex !important;
          justify-content: center !important;
        }

        .signature {
          text-align: center !important;
        }

        .signature-line {
          width: 160px !important;
          border-top: 1px solid #000 !important;
          margin: 0 auto 6px auto !important;
        }

        .signature-name {
          font-size: 12px !important;
          font-weight: 600 !important;
          color: #333 !important;
          margin: 4px 0 1px 0 !important;
        }

        .signature-title {
          font-size: 10px !important;
          color: #666 !important;
          margin: 0 !important;
        }

        /* Optimasi tanggal dan verifikasi */
        .certificate-date {
          font-size: 11px !important;
          color: #666 !important;
          margin-top: 12mm !important; /* Increased margin for better spacing */
          font-style: italic !important;
        }

        .certificate-verification {
          margin-top: 10mm !important; /* Increased margin for footer */
          padding-top: 8px !important;
          border-top: 1px solid #eee !important;
          display: flex !important;
          justify-content: space-between !important;
          align-items: center !important;
        }

        .certificate-number {
          font-size: 10px !important;
          color: #666 !important;
        }

        .qr-code img {
          width: 50px !important;
          height: 50px !important;
          border: 1px solid #ddd !important;
        }

        .qr-text {
          font-size: 9px !important;
          color: #999 !important;
          margin-top: 2px !important;
        }

        /* Watermark untuk print */
        .watermark {
          opacity: 0.03 !important;
          font-size: 80px !important;
          color: #2c3e50 !important;
          position: absolute !important;
          top: 50% !important;
          left: 50% !important;
          transform: translate(-50%, -50%) rotate(-30deg) !important;
          z-index: 0 !important;
          pointer-events: none !important;
        }

        /* Pastikan semua elemen z-index benar */
        .certificate-header,
        .certificate-body {
          z-index: 2 !important;
          position: relative !important;
        }

        /* Mencegah page break dan overflow */
        .certificate-container,
        .certificate-container * {
          page-break-inside: avoid !important;
          break-inside: avoid !important;
        }

        /* Optimasi border dan background pattern */
        .certificate-container::before,
        .certificate-container::after {
          border-color: #2c3e50 !important;
          opacity: 0.8 !important;
        }

        /* Font rendering untuk print */
        * {
          text-rendering: optimizeLegibility !important;
          -webkit-font-smoothing: antialiased !important;
        }

        /* Pastikan kontainer menggunakan seluruh halaman landscape */
        html, body {
          width: 297mm !important; /* A4 landscape width */
          height: 210mm !important; /* A4 landscape height */
          overflow: hidden !important;
          margin: 0 !important;
          padding: 0 !important;
        }

        /* Kontrol ukuran maksimal untuk mencegah overflow */
        .certificate-container {
          max-width: 257mm !important; /* A4 landscape minus left+right margins (297-40) */
          box-sizing: border-box !important;
          position: absolute !important;
          top: 0 !important;
          left: 0 !important;
          right: 0 !important;
        }

        /* Optimasi header untuk print */
        .certificate-header {
          margin-bottom: 6mm !important; /* Reduced bottom margin */
        }

        /* Optimasi body untuk print - ensure content starts from top */
        .certificate-body {
          padding-top: 0 !important; /* Remove top padding */
          margin-top: 0 !important; /* Remove top margin */
        }

        /* Sembunyikan scrollbar untuk print */
        ::-webkit-scrollbar {
          display: none !important;
        }
      }

      @media (max-width: 768px) {
        .certificate-container {
          padding: 30px 20px;
        }

        .certificate-title {
          font-size: 32px;
        }

        .recipient-name {
          font-size: 29px;
        }

        .signature-section {
          flex-direction: column;
          align-items: center;
        }

        .signature {
          margin: 20px 0;
        }

        .certificate-verification {
          flex-direction: column;
          gap: 15px;
          text-align: center;
        }

        .certificate-number {
          order: 2;
        }

        .qr-code-section {
          order: 1;
        }
      }
    </style>
  </head>
  <body>
    <!-- Certificate Action Buttons -->
    <div class="certificate-actions">
      <button class="btn-print" onclick="printCertificate()" title="Print Certificate (Ctrl+P)">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 9V2H18V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M6 18H4C3.44772 18 3 17.5523 3 17V11C3 10.4477 3.44772 10 4 10H20C20.5523 10 21 10.4477 21 11V17C21 17.5523 20.5523 18 20 18H18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M6 14H18V22H6V14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Print
      </button>
    </div>

    <div class="certificate-container">
      <div class="watermark">SERTIFIKAT</div>

      <div class="certificate-header">
        @if($certificate->event->organizer_name || isset($company))
        <img
          src="{{ asset('assets/img/logo.svg') }}"
          alt="Logo {{ $certificate->event->organizer_name ?? $company->name ?? 'Makna Academy' }}"
          class="certificate-logo"
        />
        @else
        <img
          src="{{ asset('assets/img/logo.svg') }}"
          alt="Logo Makna Academy"
          class="certificate-logo"
        />
        @endif
    <h1 class="certificate-title">Sertifikat Penyelesaian</h1>
        <div class="certificate-subtitle">
          Diberikan sebagai bukti telah menyelesaikan
        </div>
      </div>

      <div class="certificate-body">
        <div class="awarded-to">Dengan ini diberikan kepada:</div>
        <div class="recipient-name">{{ $certificate->name ?? $certificate->user->name }}</div>

        <div class="certificate-text">
          Atas partisipasi aktif dan telah menyelesaikan program
          <strong>"{{ $certificate->event->title }}"</strong>
          yang diselenggarakan oleh {{ $certificate->event->organizer_name ?? 'Makna Academy' }}
          @if($certificate->event->start_date)
            pada tanggal {{ $certificate->event->start_date->format('d') }}
            @if($certificate->event->end_date && $certificate->event->end_date != $certificate->event->start_date)
              - {{ $certificate->event->end_date->format('d') }}
            @endif
            {{ $certificate->event->start_date->format('F Y') }}.
          @else
            pada tahun {{ date('Y') }}.
          @endif
        </div>
        
        <div class="signature-section">
          <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $certificate->event->pembicara}}</div>
            <div class="signature-title">{{ $certificate->event->organizer_name ?? 'Makna Academy' }}</div>
          </div>
        </div>

    <div class="certificate-date">
          {{ $certificate->event->city ?? 'Palembang' }}, 
          {{ $certificate->completed_at ? $certificate->completed_at->format('d F Y') : now()->format('d F Y') }}
        </div>

        @if($certificate->certificate_number || isset($qrCode))
        <div class="certificate-verification">
          @if($certificate->certificate_number)
            <div class="certificate-number">
              <strong>Nomor Sertifikat:</strong> {{ $certificate->certificate_number }}
            </div>
          @endif
          
          @if(isset($qrCode))
            <div class="qr-code-section">
              <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code Verifikasi" />
              </div>
              <div class="qr-text">Scan untuk verifikasi</div>
            </div>
          @endif
        </div>
        @endif
      </div>
    </div>

    <script>
      // Enhanced print function with preview
      function printCertificate() {
        // Optimize for print
        document.body.classList.add('printing');
        
        // Use setTimeout to ensure styles are applied
        setTimeout(() => {
          window.print();
          document.body.classList.remove('printing');
        }, 100);
      }

      // Print event listeners
      window.addEventListener('beforeprint', function() {
        document.body.classList.add('printing');
      });

      window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
      });

      // Keyboard shortcuts
      document.addEventListener('keydown', function(e) {
        // Ctrl+P for print
        if (e.ctrlKey && e.key === 'p') {
          e.preventDefault();
          printCertificate();
        }
      });
    </script>
  </body>
</html>
