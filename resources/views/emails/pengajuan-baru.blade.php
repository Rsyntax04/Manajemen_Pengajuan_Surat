<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Ada Pengajuan Baru</h2>

<p>Halo {{ $mo }},</p>

<p>Pengajuan baru telah masuk.</p>

<table border="1" cellpadding="8">
    <tr>
        <td><strong>Pengaju</strong></td>
        <td>{{ $surat}}</td>
    </tr>
    <tr>
        <td><strong>Jenis Surat</strong></td>
        <td>{{ $jenis_surat }}</td>
    </tr>
</table>

<p>Silakan login ke sistem untuk melihat detailnya.</p>

</body>
</html>