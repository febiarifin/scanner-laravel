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
                    <button type="button" class="btn btn-info mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
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
                                        <div class="alert alert-primary">
                                            Download template import data presensi <a
                                                href="https://docs.google.com/spreadsheets/d/1eEQZgeS6LGC6S14TDQa_n-enHmu7lDFF/edit?usp=sharing&ouid=108100506266177956543&rtpof=true&sd=true"
                                                target="_blank"><i class="bi bi-download"></i> DOWNLOAD</a>
                                        </div>
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

                    <a href="{{ route('events.export', $event->id) }}" class="btn btn-success mb-2"><i
                            class="bi bi-download"></i> Export Data Presensi</a>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal"
                        data-bs-target="#printModal">
                        <i class="bi bi-printer"></i> Print Surat Undangan
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="printModalLabel">Print Surat Undangan</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('events.print') }}" method="post" target="_blank">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Pilih Kelas</label>
                                            <select name="kelas" id="kelas" class="form-control" required>
                                                <option value="">--pilih kelas--</option>
                                               @foreach ($classes as $class)
                                               <option value="{{ $class->kelas }}">{{ $class->kelas }}</option>
                                               @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Print</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('events.reset.present', $event->id) }}" class="btn btn-warning mb-2"
                        onclick="return confirm('Yakin ingin reset data kehadiran presensi?')"><i class="bi bi-trash"></i> Reset Data
                        Kehadiran</a>

                        <a href="{{ route('events.reset', $event->id) }}" class="btn btn-danger mb-2"
                        onclick="return confirm('Yakin ingin reset data presensi?')"><i class="bi bi-trash"></i> Reset Data
                        Presensi</a>
                </div>
                <table class="table-bordered">
                    <tr style="background-color: rgb(136, 211, 136);">
                        <th colspan="8">TOTAL KEHADIRAN</th>
                        <th colspan="2" class="text-center">
                            {{ count($event->presences()->where('is_present', 1)->get()) }}</th>
                    </tr>
                    <tr style="background-color: rgb(211, 136, 136);">
                        <th colspan="8">TOTAL TIDAK HADIR</th>
                        <th colspan="2" class="text-center">
                            {{ count($event->presences()->where('is_present', 0)->get()) }}</th>
                    </tr>
                    <tr style="background-color: rgb(136, 208, 211);">
                        <th colspan="8">TOTAL HADIR TERDAFTAR</th>
                        <th colspan="2" class="text-center">
                            {{ count($event->presences()->where('is_present', 1)->where('is_registered', 1)->get()) }}
                        </th>
                    </tr>
                    <tr style="background-color: rgb(203, 211, 136);">
                        <th colspan="8">TOTAL HADIR TIDAK TERDAFTAR</th>
                        <th colspan="2" class="text-center">
                            {{ count($event->presences()->where('is_present', 1)->where('is_registered', 0)->get()) }}
                        </th>
                    </tr>
                    <tr style="background-color: rgb(84, 158, 84);">
                        <th colspan="8">TOTAL TERDAFTAR</th>
                        <th colspan="2" class="text-center">
                            {{ count($event->presences()->where('is_registered', 1)->get()) }}
                        </th>
                    </tr>
                    <tr style="background-color: rgb(175, 97, 97);">
                        <th colspan="8">TOTAL TIDAK TERDAFTAR</th>
                        <th colspan="2" class="text-center">
                            {{ count($event->presences()->where('is_registered', 0)->get()) }}
                        </th>
                    </tr>
                </table>
                <table class="table table-bordered" id="presence_table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Nama Ayah</th>
                            <th>Nama Ibu</th>
                            <th>Alamat</th>
                            <th>Waktu Kehadiran</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
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
                                <td>
                                    <a href="{{ route('events.print.single', $presence->id) }}"
                                        class="btn btn-secondary btn-sm mr-2" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <a href="{{ route('events.change', $presence->id) }}" class="btn btn-default btn-sm">
                                        <i class="bi bi-{{ $presence->is_registered ? 'x' : 'check' }}-circle text-{{ $presence->is_registered ? 'danger' : 'success' }}"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#presence_table').DataTable();
        });
    </script>
@endsection
