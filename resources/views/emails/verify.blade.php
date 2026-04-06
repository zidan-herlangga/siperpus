<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verifikasi Email - E-Library SMK Karya Guna 2</title>
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
                            
                            <!-- Header Hijau -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #047857 0%, #065f46 100%); padding: 40px 30px; text-align: center;">
                                        <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi" width="100" style="display: block; margin: 0 auto 20px auto; filter: brightness(0) invert(1);" />
                                        <h1 style="color: #ffffff; font-size: 22px; margin: 0; font-weight: 700; letter-spacing: -0.5px;">Verifikasi Email Anda</h1>
                                        <p style="color: #a7f3d0; font-size: 13px; margin: 8px 0 0 0;">Satu langkah lagi untuk mengaktifkan akun Anda</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Isi Utama -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="padding: 40px 35px 20px 35px;">
                                        <h2 style="font-size: 20px; color: #111827; margin: 0 0 15px 0; font-weight: 700;">Halo, {{ $user->name }} 👋</h2>
                                        <p style="color: #4b5563; line-height: 1.7; font-size: 15px; margin: 0 0 10px 0;">
                                            Selamat datang di <strong style="color: #111827;">E-Library SMK Karya Guna 2</strong>! 
                                        </p>
                                        <p style="color: #4b5563; line-height: 1.7; font-size: 15px; margin: 0 0 30px 0;">
                                            Untuk mulai menggunakan layanan perpustakaan digital kami, silakan verifikasi alamat email Anda dengan mengeklik tombol di bawah ini:
                                        </p>
                                    </td>
                                </tr>

                                <!-- Tombol CTA -->
                                <tr>
                                    <td align="center" style="padding: 0 35px 30px 35px;">
                                        <a href="{{ $url }}" style="background-color: #047857; color: #ffffff; padding: 14px 40px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; display: inline-block; box-shadow: 0 4px 14px rgba(4, 120, 87, 0.35);">
                                            Verifikasi Sekarang
                                        </a>
                                    </td>
                                </tr>

                                <!-- Fallback Link -->
                                <tr>
                                    <td style="padding: 0 35px 35px 35px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border-radius: 10px; border: 1px solid #f3f4f6;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <p style="margin: 0 0 10px 0; font-size: 12px; color: #6b7280; text-align: center;">
                                                        Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:
                                                    </p>
                                                    <p style="margin: 0; font-size: 12px; color: #047857; text-align: center; word-break: break-all; font-weight: 600;">
                                                        {{ $url }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>

                            <!-- Footer Card -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background-color: #f9fafb; padding: 20px 35px; border-top: 1px solid #f3f4f6; text-align: center;">
                                        <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 12px;">
                                            Terima kasih telah bergabung bersama kami.
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
                            Email ini dikirim secara otomatis oleh sistem E-Library. Mohon untuk tidak membalas email ini.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>