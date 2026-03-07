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

                    <a href="{{ route('events.reset.present', $event->id) }}" class="btn btn-warning mb-2"
                        onclick="return confirmPin('Yakin ingin reset data kehadiran presensi?', 'fengpin')">
                        <i class="bi bi-trash"></i> Reset Data Kehadiran
                    </a>

                    <a href="{{ route('events.reset', $event->id) }}" class="btn btn-danger mb-2"
                        onclick="return confirmPin('Yakin ingin reset data presensi?', 'fengpin')">
                        <i class="bi bi-trash"></i> Reset Data Presensi
                    </a>
                </div>
                <table class="table-bordered">
                    <tr style="background-color: rgb(136, 211, 136);">
                        <th colspan="8">TOTAL KEHADIRAN</th>
                        <th colspan="2" class="text-center">
                            {{ count($event->presences()->where('is_present', 1)->get()) }}</th>
                    </tr>
                </table>
                <table class="table table-bordered" id="presence_table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>Waktu Kehadiran</th>
                            <th>Status</th>
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
                                <td>{{ $presence->date }}</td>
                                <td class="{{ $presence->is_present ? 'bg-success' : 'bg-danger' }} text-white">
                                    {{ $presence->is_present ? 'HADIR' : 'TIDAK HADIR' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $presence->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $presence->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('presences.update', $presence->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Presensi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label>Nama Guru / Code</label>
                                                    <input type="text" name="code" class="form-control"
                                                        value="{{ $presence->code }}" required>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
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

        function confirmPin(message, pin) {
            if (confirm(message)) {
                let input = prompt("Masukkan PIN untuk konfirmasi:");
                if (input === pin) {
                    return true; // lanjut ke route
                } else {
                    alert("PIN salah! Aksi dibatalkan.");
                    return false; // batalkan
                }
            }
            return false;
        }
    </script>
@endsection
