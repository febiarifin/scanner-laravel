<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="{{ asset('html5-qrcode.min.js') }}"></script>
    <style>
        table,
        tr,
        td {
            font-size: 10pt;
        }
    </style>
</head>

<body>
    <div class="container mt-2">
        <div class="mb-3 d-flex">
            <div class="flex-grow-1">
                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-secondary shadow btn-sm"><i
                        class="bi bi-arrow-left"></i> Rekap Presensi</a>
            </div>
            <h4 class="flex-shrink-0">#{{ $event->name }}</h4>
        </div>

        <form id="presenceForm" class="mb-1 row">
            <div class="col-10">
                <input type="text" name="presence_code" id="presence_code" class="form-control"
                    placeholder="Inputkan NIS / Nama Siswa" required>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary btm-sm"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div id="qr-reader" style="width: 100%"></div>

        <div class="text-center">
            TOTAL KEHADIRAN
            <h1 class="text-success" id="counter">{{ count($count_presences) }}</h1>
        </div>

        <span class="fw-bold">HASIL: <span id="messageResult" class="mb-1"></span></span>

        <div id="scannerResult" class="mb-2">Silahkah scan terlebih dahulu</div>

        <table id="presence_table" class="table table-bordered">
            <thead>
                <tr>
                    <th>KODE USER</th>
                    <th>TANGGAL KEHADIRAN</th>
                    <th>STATUS</th>
                    <th>TERDAFTAR</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presences as $presence)
                    <tr>
                        <td>{{ $presence->code }}</td>
                        <td>{{ $presence->date }}</td>
                        <td class="bg-success text-white">HADIR</td>
                        <td class="{{ $presence->terdaftar ? 'bg-success' : 'bg-danger' }} text-white">
                            {{ $presence->terdaftar ? 'YA' : 'TIDAK' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <audio id="myAudio">
        <source src="{{ asset('sounds/qrcode.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var x = document.getElementById("myAudio");

        function playAudio() {
            x.play();
        }

        function onScanSuccess(decodedText, decodedResult) {
            scannerResult = decodedText;

            $.ajax({
                url: '{{ route('scanner.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    code: scannerResult,
                },
                success: function(response) {
                    // console.log(response);
                    playAudio();
                    if (response['detail']) {
                        let terdaftar = response['detail'].is_registered == 1 ? '<b>TERDAFTAR</b>' :
                            '<b>TIDAK TERDAFTAR</b>';
                        let message = 'NIS: <b>' + response['detail'].code + '</b><br>Nama: <b>' + response[
                                'detail']
                            .name + '</b><br>Nama Ayah: <b>' + response['detail']
                            .father_name + '</b><br>Nama Ibu: <b>' + response['detail'].mother_name +
                            '</b><br>Kelas: <b>' +
                            response['detail'].kelas + '</b><br>Alamat: <b>' + response['detail'].address +
                            '</b><br>Status: ';
                        $('#scannerResult').html(message + terdaftar)
                            .addClass('p-3 rounded border border-primary');;
                    } else {
                        $('#scannerResult').html("Data kehadiran tidak ditemukan");
                    }

                    if (response['status'] == true) {
                        $('#messageResult')
                            .html(response['message'])
                            .removeClass('badge text-bg-danger')
                            .addClass('badge text-bg-success');
                    } else {
                        $('#messageResult')
                            .html(response['message'])
                            .removeClass('badge text-bg-success')
                            .addClass('badge text-bg-danger');
                    }

                    $('#counter').html(response['counter']);

                    var tableBody = $('#presence_table tbody');
                    tableBody.empty();

                    if (Array.isArray(response.presences) && response.presences.length > 0) {
                        $.each(response.presences, function(index, presence) {
                            var terdaftarStatus = presence.is_registered == 1 ?
                                '<td class="bg-success text-white">IYA</td>' :
                                '<td class="bg-danger text-white">TIDAK</td>';

                            var row = '<tr>' +
                                '<td>' + presence.code + '</td>' +
                                '<td>' + presence.date + '</td>' +
                                '<td class="bg-success text-white">HADIR</td>' +
                                terdaftarStatus +
                                '</tr>';
                            tableBody.append(row);
                        });
                    } else {
                        tableBody.append('<tr><td colspan="4">No presence data found</td></tr>');
                    }
                }
            });
        }
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                rememberLastUsedCamera: true,
                showTorchButtonIfSupported: true
            });

        html5QrcodeScanner.render(onScanSuccess);

        $(document).ready(function() {
            $('#presenceForm').on('submit', function(e) {
                e.preventDefault();
                var presenceCode = $('#presence_code').val();
                $.ajax({
                    url: "{{ route('events.presence.manual') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        presence_code: presenceCode
                    },
                    success: function(response) {
                        // console.log(response);
                        playAudio();

                        if (response['detail']) {
                            if (response['detail']) {
                                let terdaftar = response['detail'].is_registered == 1 ?
                                    '<b>TERDAFTAR</b>' :
                                    '<b>TIDAK TERDAFTAR</b>';
                                let message = 'NIS: <b>' + response['detail'].code +
                                    '</b><br>Nama: <b>' + response[
                                        'detail']
                                    .name + '</b><br>Nama Ayah: <b>' + response['detail']
                                    .father_name + '</b><br>Nama Ibu: <b>' + response['detail']
                                    .mother_name +
                                    '</b><br>Kelas: <b>' +
                                    response['detail'].kelas + '</b><br>Alamat: <b>' + response[
                                        'detail'].address +
                                    '</b><br>Status: ';
                                $('#scannerResult').html(message + terdaftar)
                                    .addClass('p-3 rounded border border-primary');;
                            } else {
                                $('#scannerResult').html("Data kehadiran tidak ditemukan");
                            }

                            $('#messageResult')
                                .html(response['message'])
                                .removeClass('badge text-bg-danger')
                                .addClass('badge text-bg-success');

                            $('#counter').html(response['counter']);

                            var tableBody = $('#presence_table tbody');
                            tableBody.empty();

                            if (Array.isArray(response.presences) && response.presences.length >
                                0) {
                                $.each(response.presences, function(index, presence) {
                                    var terdaftarStatus = presence.is_registered == 1 ?
                                        '<td class="bg-success text-white">IYA</td>' :
                                        '<td class="bg-danger text-white">TIDAK</td>';

                                    var row = '<tr>' +
                                        '<td>' + presence.code + '</td>' +
                                        '<td>' + presence.date + '</td>' +
                                        '<td class="bg-success text-white">HADIR</td>' +
                                        terdaftarStatus +
                                        '</tr>';
                                    tableBody.append(row);
                                });
                            } else {
                                tableBody.append(
                                    '<tr><td colspan="4">No presence data found</td></tr>');
                            }
                        } else {
                            $('#messageResult')
                                .html(response['message'])
                                .removeClass('badge text-bg-success')
                                .addClass('badge text-bg-danger');
                            $('#scannerResult').html("Data kehadiran tidak ditemukan");
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#messageResult')
                            .html('Terjadi kesalahan')
                            .removeClass('badge text-bg-success')
                            .addClass('badge text-bg-danger');
                        $('#scannerResult').html("Data kehadiran tidak ditemukan");
                    }
                });
            });
        });
    </script>
</body>

</html>
