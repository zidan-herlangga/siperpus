<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Keterlambatan Pengembalian Buku</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 20px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="border-collapse: collapse; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

                    <tr>
                        <td align="center"
                            style="background-color: #d32f2f; padding: 30px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                            <img src="{{ asset('assets/image/logo-light-smkg2.png') }}"
                                alt="Logo SMK Karya Guna 2 Bekasi" width="150" style="display: block;" />
                            <h1 style="color: #ffffff; font-size: 24px; margin: 10px 0 0 0;">Notifikasi Keterlambatan
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="font-size: 20px; color: #333333; margin: 0 0 20px 0;">Halo,
                                {{ $borrowing->student->name }}!</h2>
                            <p style="color: #555555; line-height: 1.6;">
                                Kami informasikan bahwa buku yang Anda pinjam telah melewati batas waktu pengembalian.
                                Mohon untuk segera mengembalikannya ke perpustakaan.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="border: 1px solid #eeeeee; border-radius: 8px; padding: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #555555;"><strong>Judul Buku:</strong>
                                            {{ $borrowing->book->title }}</p>
                                        <p style="margin: 0; color: #555555;"><strong>Jatuh Tempo:</strong> <span
                                                style="font-weight: bold; color: #d32f2f;">{{ $borrowing->due_date->format('d F Y') }}</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 30px;">
                            <div
                                style="background-color: #fff3cd; border-radius: 8px; padding: 20px; text-align: center;">
                                <p style="margin: 0; font-size: 16px; color: #856404;">Total Denda Saat Ini:</p>
                                <p style="margin: 10px 0 0 0; font-size: 28px; font-weight: bold; color: #d32f2f;">
                                    Rp{{ number_format($fine, 0, ',', '.') }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 30px 40px 30px;">
                            <a href="{{ route('student.dashboard') }}"
                                style="background-color: #006739; color: #ffffff; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;">
                                Lihat Dashboard Saya
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px; font-size: 12px; color: #888888;">
                <p style="margin: 0;">&copy; {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi. Hak Cipta
                    Dilindungi.</p>
            </td>
        </tr>
    </table>
</body>

</html>
