<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Status Reservasi Ruangan</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding: 24px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px border #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #059669; /* Emerald 600 */
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.025em;
        }
        .header p {
            color: #a7f3d0;
            font-size: 13px;
            margin: 4px 0 0 0;
            font-weight: 500;
        }
        .content {
            padding: 32px 24px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 8px;
        }
        .message {
            font-size: 14px;
            line-height: 20px;
            color: #475569;
            margin-top: 0;
            margin-bottom: 24px;
        }
        .alert-box {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 13px;
            line-height: 18px;
        }
        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .details-table th, .details-table td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }
        .details-table th {
            width: 35%;
            text-align: left;
            font-weight: 600;
            color: #64748b;
            background-color: #f8fafc;
        }
        .details-table td {
            color: #334155;
            font-weight: 500;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>UPT Perpustakaan IAIN Sorong</h1>
                <p>Sistem Informasi Layanan Peminjaman Ruang</p>
            </div>
            
            <div class="content">
                <p class="greeting">Halo, {{ $reservation->name }}</p>
                
                @if($reservation->status === 'approved')
                    <p class="message">Permohonan peminjaman ruangan Anda telah ditinjau dan <strong>DISETUJUI</strong> oleh petugas perpustakaan.</p>
                    
                    <div class="alert-box alert-success">
                        <strong>📌 Instruksi Pengambilan Kunci:</strong><br>
                        Silakan tunjukkan email ini kepada petugas di meja pelayanan perpustakaan untuk mengambil kunci ruangan paling lambat 15 menit sebelum waktu peminjaman Anda dimulai.
                    </div>
                @else
                    <p class="message">Permohonan peminjaman ruangan Anda telah ditinjau dan <strong>DITOLAK</strong> oleh petugas perpustakaan.</p>
                    
                    <div class="alert-box alert-danger">
                        <strong>Alasan Penolakan:</strong><br>
                        {{ $reservation->rejection_reason }}
                    </div>
                @endif
                
                <table class="details-table">
                    <tr>
                        <th>Ruangan</th>
                        <td>{{ $reservation->room_name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Booking</th>
                        <td>{{ \Carbon\Carbon::parse($reservation->booking_date)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Sesi Waktu</th>
                        <td>{{ $reservation->session_time }}</td>
                    </tr>
                    <tr>
                        <th>Kode Peminjaman</th>
                        <td><code style="font-family: monospace; font-weight: bold; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">#ROOM-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</code></td>
                    </tr>
                </table>
                
                <p style="font-size: 13px; color: #64748b; line-height: 18px; margin: 0;">
                    Jika ada pertanyaan lebih lanjut, silakan hubungi meja layanan informasi UPT Perpustakaan IAIN Sorong. Terima kasih.
                </p>
            </div>
            
            <div class="footer">
                <p>&copy; {{ date('Y') }} UPT Perpustakaan IAIN Sorong. All rights reserved.</p>
                <p>Email ini dikirim secara otomatis oleh sistem perpustakaan, mohon tidak membalas email ini.</p>
            </div>
        </div>
    </div>
</body>
</html>
