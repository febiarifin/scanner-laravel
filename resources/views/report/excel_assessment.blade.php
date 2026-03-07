<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REKAP PRESENSI</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="4" style="text-align: center; border: 1px solid black; font-weight: bold;">REKAP PRESENSI EVENT #{{ $event->name }} <br> Tanggal
                    Export {{ now()->format('d/m/Y H:i:s') }}</th>
            </tr>
            <tr>
                <th style="border: 1px solid black; font-weight: bold;" width="10">#</th>
                <th style="border: 1px solid black; font-weight: bold;" width="40">NAMA GURU</th>
                <th style="border: 1px solid black; font-weight: bold;" width="40">Waktu Kehadiran</th>
                <th style="border: 1px solid black; font-weight: bold;" width="15">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($presences as $presence)
                <tr>
                    <td style="border: 1px solid black;">{{ $no++ }}</td>
                    <td style="border: 1px solid black;">{{ $presence->code }}</td>
                    <td style="border: 1px solid black;">{{ $presence->date }}</td>
                    <td style="border: 1px solid black; background-color: {{ $presence->is_present ? 'green' : 'red' }};">
                        {{ $presence->is_present ? 'HADIR' : 'TIDAK HADIR' }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3" style="border: 1px solid black;">TOTAL KEHADIRAN</th>
                <th style="text-align: center; font-weight: bold; border: 1px solid black;">
                    {{ count($event->presences()->where('is_present', 1)->get()) }}</th>
            </tr>
        </tbody>
    </table>

</body>

</html>
