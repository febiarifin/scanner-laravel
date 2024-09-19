<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        .paper {
            height: 1060px;
            display: flex;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    @if ($is_single)
        <div class="paper">
            <div style="margin-left: -10px; margin-top:20px;">
                <img src="{{ asset('templete_surat_new_1.jpg') }}" width="700px">
            </div>
            <div style="margin-left: -400px; width: 500px !important;">
                <div>
                    <span
                        style="font-size: {{ strlen($presence->name) > 16 ? 12 : 12 }}pt; position: relative; top: 196px; left: 174px;">{{ $presence->name }}<br>
                        {{ $presence->code }} / {{ $presence->kelas }}</span>
                </div>
                <div style="position: relative; top: 740px; left: 6px;">
                    <div style="padding: 2px; border: 4px solid black; width: 36%; height: 180px;">
                        {!! QrCode::size(180)->generate(base64_encode($presence->code)) !!}
                    </div>
                </div>
            </div>
        </div>
    @else
        @foreach ($presences as $presence)
            <div class="paper">
                <div style="margin-left: -10px; margin-top:20px;">
                    <img src="{{ asset('templete_surat_new_1.jpg') }}" width="700px">
                </div>
                <div style="margin-left: -400px; width: 500px !important;">
                    <div>
                        <span
                            style="font-size: {{ strlen($presence->name) > 16 ? 12 : 12 }}pt; position: relative; top: 196px; left: 174px;">{{ $presence->name }}<br>
                            {{ $presence->code }} / {{ $presence->kelas }}</span>
                    </div>
                    <div style="position: relative; top: 740px; left: 6px;">
                        <div style="padding: 2px; border: 4px solid black; width: 36%; height: 180px;">
                            {!! QrCode::size(180)->generate(base64_encode($presence->code)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</body>

</html>
