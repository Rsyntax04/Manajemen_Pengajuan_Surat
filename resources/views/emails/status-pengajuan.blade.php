<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Status Pengajuan Surat</h2>

<p>Halo {{ $surat->nama_mahasiswa }},</p>

<p>Status pengajuan surat Anda telah berubah.</p>

<table border="1" cellpadding="8">
    <tr>
        <td><strong>Jenis Surat</strong></td>
        <td>{{ $jenis_surat }}</td>
    </tr>
    <tr>
        <td><strong>Status</strong></td>
        <td>{{ $surat->status }}</td>
    </tr>
    <tr>
        <td>Catatan</td>
        <td>{{ $pesan }}</td>
    </tr>
</table>

<p>Silakan login ke sistem untuk melihat detailnya.</p>

</body>
</html>