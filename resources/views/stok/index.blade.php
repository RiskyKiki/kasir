@extends('layouts.app')

@section('title', 'Stok Barang')

@section('subtitle', 'Laporan Stok Barang')

@section('content')
    <div class="section-body">
        <!-- Tombol Export Excel -->
        <div class="d-flex align-items-end">
            <div class="ml-auto text-right">
                <div class="mb-3">
                    <a href="{{ route('stok.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto">
                    <div id="TableContainer">
                        @include('stok.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Inisialisasi DataTable saat document ready
        $(document).ready(function() {
            $('#myTable').DataTable({
                "columnDefs": [{
                        "orderable": false,
                        "targets": 5
                    },
                    {
                        "searchable": false,
                        "targets": 5
                    },
                    {
                        "targets": 0,
                        "width": "100px",
                        "className": "dt-center"
                    },
                    {
                        "targets": [1, 2, 3, 4, 5],
                        "width": "120px"
                    }
                ],
                "order": [
                    [0, 'asc']
                ],
                "responsive": true
            });


        });
    </script>
@endpush
