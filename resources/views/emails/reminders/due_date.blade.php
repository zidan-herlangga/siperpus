<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Batas Waktu Pengembalian Buku</title>
</head>
<body>
    <h1>Halo, {{ $borrowing->student->name }}!</h1>
    <p>
        Ini adalah pengingat bahwa buku yang Anda pinjam, <strong>"{{ $borrowing->book->title }}"</strong>,
        akan jatuh tempo besok, pada tanggal <strong>{{ $borrowing->due_date->format('d F Y') }}</strong>.
    </p>
    <p>
        Mohon untuk segera mengembalikannya ke perpustakaan untuk menghindari denda.
    </p>
    <p>Terima kasih!</p>
</body>
</html>