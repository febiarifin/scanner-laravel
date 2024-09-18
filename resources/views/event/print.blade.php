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
        }
    </style>
</head>

<body>
    @if ($is_single)
        <div class="paper">
            <div>
                <img src="{{ asset('templete_surat_new.jpg') }}" width="800px">
            </div>
            <div style="position: relative; left: -200px;">
                <div>
                    <span
                        style="font-size: 14pt; position: relative; top: 185px; left: -115px;">{{ $presence->name }}
                        / {{ $presence->code }}</span>
                </div>
                <div style="position: relative; top: 800px; left: -265px;">{!! QrCode::size(150)->generate(base64_encode($presence->code)) !!}</div>
            </div>
        </div>
    @else
        @foreach ($presences as $presence)
            <div class="paper">
                <div>
                    <img src="{{ asset('templete_surat_new.jpg') }}" width="800px">
                </div>
                <div style="position: relative; left: -200px;">
                    <div>
                        <span
                            style="font-size: 14pt; position: relative; top: 185px; left: -115px;">{{ $presence->name }}
                            / {{ $presence->code }}</span>
                    </div>
                    <div style="position: relative; top: 800px; left: -265px;">{!! QrCode::size(150)->generate(base64_encode($presence->code)) !!}</div>
                </div>
            </div>
        @endforeach
    @endif
</body>

</html>
