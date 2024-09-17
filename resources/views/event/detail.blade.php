@extends('layouts.template')

@section('content')
    <div class="mt-5">
        <a href="{{ route('events.index') }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div class="card">
            <div class="card-header d-flex">
                <div class="card-title flex-grow-1 fs-3">{{ $event->name }}</div>
                <div class="flex-shrink-0">
                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-lg shadow">
                        Presensi Kehadiran <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <div class="mb-3">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <i class="bi bi-upload"></i> Import Data Presensi
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Import Data Presensi</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('events.import') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>File</label>
                                            <input type="file" name="file" class="form-control" accept=".xlsx, .csv"
                                                required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Import</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('events.export', $event->id) }}" class="btn btn-secondary mb-2"><i class="bi bi-download"></i> Export Data Presensi</a>
                </div>
                <table class="table table-bordered">
                    <thead>
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
                                <td class="{{ $presence->is_present ? 'bg-success' : 'bg-danger' }} text-white">
                                    {{ $presence->is_present ? 'HADIR' : 'TIDAK HADIR' }}</td>
                                <td class="{{ $presence->is_registered ? 'bg-success' : 'bg-danger' }}  text-white">
                                    {{ $presence->is_registered ? 'IYA' : 'TIDAK' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="8">TOTAL KEHADIRAN</th>
                            <th colspan="2" class="text-center">
                                {{ count($event->presences()->where('is_present', 1)->get()) }}</th>
                        </tr>
                        <tr>
                            <th colspan="8">TOTAL TIDAK HADIR</th>
                            <th colspan="2" class="text-center">
                                {{ count($event->presences()->where('is_present', 0)->get()) }}</th>
                        </tr>
                        <tr>
                            <th colspan="8">TOTAL HADIR TERDAFTAR</th>
                            <th colspan="2" class="text-center">
                                {{ count($event->presences()->where('is_present', 1)->where('is_registered', 1)->get()) }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="8">TOTAL HADIR TIDAK TERDAFTAR</th>
                            <th colspan="2" class="text-center">
                                {{ count($event->presences()->where('is_present', 1)->where('is_registered', 0)->get()) }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
