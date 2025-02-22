@extends('layouts.app')

@section('title', 'Log')

@section('subtitle', 'Log Aktivitas')

@section('content')
<div class="container">
    <table id="myTable" class="table table-bordered display table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Properti</th>
                <th>User</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $index => $activity)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $activity->log_name }}</td>
                <td>{{ $activity->description }}</td>
                <td>{{ json_encode($activity->properties->toArray()) }}</td>
                <td>{{ $activity->causer ? $activity->causer->username : '-' }}</td>
                <td>{{ $activity->created_at->format('d M Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script> //datatable
    $(document).ready(function() {
        console.log("Inisialisasi DataTable...");
                $('#myTable').DataTable({
                    "autoWidth": false,
                    "columnDefs": [
                        {"targets": [1, 2, 3, 4, 5]},
                        { "width": "5px", "targets": 0 },
                        { "width": "5px", "targets": 1 },
                        { "width": "10px", "targets": 2 },
                        { "width": "10px", "targets": 3 }, 
                    ],
                    "order": [
                        [0, 'asc']
                    ],
                    "scrollX": true,
                    "responsive": false
                });
            });
</script>
@endpush