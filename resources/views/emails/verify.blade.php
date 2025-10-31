@component('mail::message')
# Halo {{ $user->name }}

Selamat datang di **E-Library SMK Karya Guna 2**!  
Klik tombol di bawah ini untuk memverifikasi alamat email Anda.

@component('mail::button', ['url' => $url, 'color' => 'success'])
Verifikasi Sekarang
@endcomponent

Jika tombol tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:  
[{{ $url }}]({{ $url }})

Terima kasih telah bergabung bersama kami
Salam,  
**Elibrary SMK Karya Guna 2**
@endcomponent
