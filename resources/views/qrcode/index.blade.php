<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>barcode Scanner</title>
    <script src="{{ asset('html5-qrcode.min.js') }}"></script>
    <style>
        #presence_table, #presence_table th, #presence_table td {
            border: 1px solid black; /* Add borders to table, headers, and cells */
            border-collapse: collapse; /* Ensure borders collapse for cleaner look */
            padding: 8px; /* Add padding for better readability */
            text-align: left; /* Align text to the left */
        }

        #presence_table th {
            background-color: #f2f2f2; /* Optional: Add background color to the headers */
        }
    </style>

</head>

<body>
    <div id="qr-reader" style="width: 100%"></div>
    <h1>Hasil : </h1>
    <table id="presence_table" class="table-bordered">
        <thead>
            <tr>
                <th>KODE USER</th>
                <th>TANGGAL KEHADIRAN</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be appended here -->
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
                    // Tampilkan response['data'] ke dalam presence_table
                    // Assuming response.data is an array of objects
                    var tableBody = $('#presence_table tbody');
                    tableBody.empty(); // Clear the table body before appending new data

                    $.each(response.data, function(index, attendance) {
                        var row = '<tr>' +
                            '<td>' + attendance.code + '</td>' +
                            '<td>' + attendance.date + '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
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
