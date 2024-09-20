@extends('layouts.template')

@section('content')
    <div class="card mt-5">
        <div class="card-header d-flex">
            <div class="card-title flex-grow-1">List Event</div>
            <div class="flex-shrink-0">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus"></i> Tambah Event
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Event</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('events.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Nama Event</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div>
                                        <label>Tanggal Event</label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <td>Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->date }}</td>
                            <td class="d-flex">
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-success btn-sm shadow mx-2">Presensi Kehadiran <i
                                    class="bi bi-arrow-right"></i></a>
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary btn-sm shadow"><i
                                        class="bi bi-info-circle"></i> Detail</a> &nbsp;
                                <form action="{{ route('events.destroy', $event->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm shadow"
                                        onclick="return confirm('Yakin ingin dihapus?')"><i class="bi bi-trash"></i>
                                        Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {!! $events->links() !!}
            </div>
        </div>
    </div>
@endsection
