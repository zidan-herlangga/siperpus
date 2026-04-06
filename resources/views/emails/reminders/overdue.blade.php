<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notifikasi Keterlambatan Pengembalian Buku</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f3f4f6;">
        <!-- Spacer Atas -->
        <tr>
            <td style="padding: 40px 20px;"></td>
        </tr>
        
        <tr>
            <td align="center" style="padding: 0 20px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; border-collapse: collapse;">
                    
                    <!-- Kontainer Utama (Card) -->
                    <tr>
                        <td style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e5e7eb;">
                            
                            <!-- Header Merah -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); padding: 40px 30px; text-align: center;">
                                        <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi" width="100" style="display: block; margin: 0 auto 20px auto; filter: brightness(0) invert(1);" />
                                        <h1 style="color: #ffffff; font-size: 22px; margin: 0; font-weight: 700; letter-spacing: -0.5px;">Notifikasi Keterlambatan</h1>
                                        <p style="color: #fca5a5; font-size: 13px; margin: 8px 0 0 0;">Pengembalian buku telah melewati batas waktu</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Isi Utama -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="padding: 40px 35px 20px 35px;">
                                        <h2 style="font-size: 20px; color: #111827; margin: 0 0 15px 0; font-weight: 700;">Halo, {{ $borrowing->student->name }}</h2>
                                        <p style="color: #4b5563; line-height: 1.7; font-size: 15px; margin: 0 0 30px 0;">
                                            Kami informasikan bahwa buku yang Anda pinjam telah <strong style="color: #dc2626;">melewati batas waktu pengembalian</strong>. Mohon untuk segera mengembalikannya ke perpustakaan untuk menghentikan akumulasi denda.
                                        </p>
                                    </td>
                                </tr>

                                <!-- Kartu Detail Buku -->
                                <tr>
                                    <td style="padding: 0 35px 20px 35px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; border: 1px solid #f3f4f6;">
                                            <tr>
                                                <td style="padding: 25px;">
                                                    <!-- Judul Buku -->
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 15px;">
                                                        <tr>
                                                            <td style="padding-bottom: 15px; border-bottom: 1px dashed #e5e7eb;">
                                                                <p style="margin: 0 0 4px 0; font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">📚 Judul Buku</p>
                                                                <p style="margin: 0; color: #111827; font-size: 16px; font-weight: 700;">{{ $borrowing->book->title }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    
                                                    <!-- Info Tanggal & Denda -->
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td width="50%" style="padding-right: 10px;">
                                                                <p style="margin: 0 0 4px 0; font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">📅 Jatuh Tempo</p>
                                                                <p style="margin: 0; color: #dc2626; font-size: 15px; font-weight: 700;">{{ $borrowing->due_date->format('d F Y') }}</p>
                                                            </td>
                                                            <td width="50%" style="padding-left: 10px; border-left: 1px solid #e5e7eb;">
                                                                <p style="margin: 0 0 4px 0; font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">⏳ Terlambat</p>
                                                                @php
                                                                    $daysLate = now()->diffInDays($borrowing->due_date);
                                                                @endphp
                                                                <p style="margin: 0; color: #374151; font-size: 15px; font-weight: 700;">{{ $daysLate }} Hari</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Highlight Box Denda -->
                                <tr>
                                    <td style="padding: 0 35px 30px 35px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; text-align: center;">
                                            <tr>
                                                <td style="padding: 25px;">
                                                    <p style="margin: 0 0 5px 0; font-size: 12px; color: #991b1b; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Total Denda Saat Ini</p>
                                                    <p style="margin: 0; font-size: 32px; font-weight: 800; color: #dc2626; letter-spacing: -1px;">
                                                        Rp {{ number_format($fine, 0, ',', '.') }}
                                                    </p>
                                                    <p style="margin: 8px 0 0 0; font-size: 11px; color: #b91c1c;">
                                                        *Denda akan terus bertambah Rp 1.000/hari hingga buku dikembalikan
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Tombol CTA -->
                                <tr>
                                    <td align="center" style="padding: 10px 35px 40px 35px;">
                                        <a href="{{ route('student.dashboard') }}" style="background-color: #047857; color: #ffffff; padding: 14px 32px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 14px; display: inline-block; box-shadow: 0 4px 14px rgba(4, 120, 87, 0.35);">
                                            Selesaikan Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Footer Card -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background-color: #f9fafb; padding: 20px 35px; border-top: 1px solid #f3f4f6; text-align: center;">
                                        <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 12px;">
                                            Segera kembalikan buku ke petugas perpustakaan untuk menghentikan denda.
                                        </p>
                                        <p style="margin: 0; color: #9ca3af; font-size: 11px;">
                                            &copy; {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
                
                <!-- Sub Footer Luar -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px;">
                    <tr>
                        <td align="center" style="padding: 10px 0 40px 0; font-size: 11px; color: #9ca3af;">
                            Email ini dikirim secara otomatis oleh sistem Perpustakaan Digital. Mohon untuk tidak membalas email ini.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>