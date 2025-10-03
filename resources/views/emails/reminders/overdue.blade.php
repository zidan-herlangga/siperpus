<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Keterlambatan Pengembalian Buku</title>
</head>
<body>
    <h1>Halo, {{ $borrowing->student->name }}!</h1>
    <p>
        Buku yang Anda pinjam, <strong>"{{ $borrowing->book->title }}"</strong>, telah melewati batas waktu pengembalian
        pada tanggal <strong>{{ $borrowing->due_date->format('d F Y') }}</strong>.
    </p>
    <p>
        Anda telah terlambat mengembalikan buku ini. Denda Anda saat ini adalah:
        <strong>Rp{{ number_format($fine, 0, ',', '.') }}</strong>.
    </p>
    <p>
        Mohon segera kembalikan buku tersebut ke perpustakaan. Denda akan terus bertambah setiap harinya.
    </p>
    <p>Terima kasih.</p>
</body>
</html>