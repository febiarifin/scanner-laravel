<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>barcode Scanner</title>
    <script src="{{ asset('html5-qrcode.min.js') }}"></script>
    <style>
        #presence_table,
        #presence_table th,
        #presence_table td {
            border: 1px solid black;
            /* Add borders to table, headers, and cells */
            border-collapse: collapse;
            /* Ensure borders collapse for cleaner look */
            padding: 8px;
            /* Add padding for better readability */
            text-align: left;
            /* Align text to the left */
            font-size: 10pt;
        }

        #presence_table th {
            background-color: #f2f2f2;
            /* Optional: Add background color to the headers */
            font-size: 10pt;
        }
    </style>

</head>

<body>
    <div id="qr-reader" style="width: 100%"></div>
    <h1>Hasil : </h1>
    <h3 id="scannerResult">Silahkah scan terlebih dahulu</h3>
    <h3 id="messageResult" style="color: red;"></h3>
    <table id="presence_table" class="table-bordered">
        <thead>
            <tr>
                <th width="80">KODE USER</th>
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
                    <td bgcolor="green" style="color:white;">HADIR</td>
                    <td bgcolor="{{ $presence->terdaftar ? 'green' : 'red' }}" style="color:white;">
                        {{ $presence->terdaftar ? 'YA' : 'TIDAK' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

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
                url: '/post',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: scannerResult
                },
                success: function(response) {
                    console.log(response);
                    playAudio();
                    $('#scannerResult').html(response['code']);
                    $('#messageResult').html(response['message']);
                    // Assuming response contains the updated presence data
                    var tableBody = $('#presence_table tbody');
                    tableBody.empty(); // Clear existing rows

                    if (Array.isArray(response.presences) && response.presences.length > 0) {
                        // Loop through the presences and append each to the table
                        $.each(response.presences, function(index, presence) {
                            var terdaftarStatus = presence.terdaftar ?
                                '<td bgcolor="red" style="color:white;">TIDAK</td>' :
                                '<td bgcolor="green" style="color:white;">IYA</td>';

                            var row = '<tr>' +
                                '<td>' + presence.code + '</td>' +
                                '<td>' + presence.date + '</td>' +
                                '<td bgcolor="green" style="color:white;">HADIR</td>' +
                                terdaftarStatus +
                                '</tr>';
                            tableBody.append(row);
                        });
                    } else {
                        tableBody.append('<tr><td colspan="2">No presence data found</td></tr>');
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
    </script>
</body>

</html>
