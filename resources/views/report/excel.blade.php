<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REKAP PRESENSI</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="10" style="text-align: center;">REKAP PRESENSI EVENT #{{ $event->name }} <br> Tanggal Export {{ now()->format('d/m/Y H:i:s') }}</th>
            </tr>
            <tr>
                <th>#</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Nama Ayah</th>
                <th>Nama Ibu</th>
                <th>Alamat</th>
                <th>Waktu Kehadiran</th>
                <th>Status</th>
                <th>Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($presences as $presence)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $presence->code }}</td>
                    <td>{{ $presence->name }}</td>
                    <td>{{ $presence->kelas }}</td>
                    <td>{{ $presence->father_name }}</td>
                    <td>{{ $presence->mother_name }}</td>
                    <td>{{ $presence->address }}</td>
                    <td>{{ $presence->date }}</td>
                    <td bgcolor="{{ $presence->is_present ? 'green' : 'red' }}">
                        {{ $presence->is_present ? 'HADIR' : 'TIDAK HADIR' }}</td>
                    <td bgcolor="{{ $presence->is_registered ? 'green' : 'red' }}">
                        {{ $presence->is_registered ? 'IYA' : 'TIDAK' }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="8">TOTAL KEHADIRAN</th>
                <th colspan="2" style="text-align: center; font-weight: bold;">
                    {{ count($event->presences()->where('is_present', 1)->get()) }}</th>
            </tr>
            <tr>
                <th colspan="8">TOTAL TIDAK HADIR</th>
                <th colspan="2" style="text-align: center; font-weight: bold;">
                    {{ count($event->presences()->where('is_present', 0)->get()) }}</th>
            </tr>
            <tr>
                <th colspan="8">TOTAL HADIR TERDAFTAR</th>
                <th colspan="2" style="text-align: center; font-weight: bold;">
                    {{ count($event->presences()->where('is_present', 1)->where('is_registered', 1)->get()) }}
                </th>
            </tr>
            <tr>
                <th colspan="8">TOTAL HADIR TIDAK TERDAFTAR</th>
                <th colspan="2" style="text-align: center; font-weight: bold;">
                    {{ count($event->presences()->where('is_present', 1)->where('is_registered', 0)->get()) }}
                </th>
            </tr>
        </tbody>
    </table>

</body>
</html>
