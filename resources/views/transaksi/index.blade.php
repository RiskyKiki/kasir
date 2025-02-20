@extends('layouts.app')

@section('title', 'Transaksi')

@section('subtitle', 'Laporan Transaksi')

@section('content')

    <div class="section-body">
        <!-- Form filter rentang tanggal dan export Excel -->
        <form action="{{ route('transaksi.index') }}" method="GET" class="mb-3" id="filterForm">
            <div class="d-flex align-items-end">
                <div class="mr-3">
                    <label for="start_date" class="mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                </div>
                <div>
                    <label for="end_date" class="mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                </div>
                <div class="ml-auto text-right">
                  <button type="submit"
                      formaction="{{ route('transaksi.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                      class="btn btn-success">
                      <i class="fas fa-file-excel"></i> Export Excel
                  </button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto">
                    <div id="TableContainer">
                        @include('transaksi.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Inisialisasi DataTable saat document ready
            $(document).ready(function() {
                $('#myTable').DataTable({
                    "columnDefs": [{
                            "orderable": false,
                            "targets": 6
                        },
                        {
                            "searchable": false,
                            "targets": 6
                        },
                        {
                            "targets": 0,
                            "width": "100px",
                            "className": "dt-center"
                        },
                        {
                            "targets": [1, 2, 3, 4, 5, 6],
                            "width": "120px"
                        }
                    ],
                    "order": [
                        [0, 'asc']
                    ],
                    "responsive": true
                });

                // Otomatis submit form saat input tanggal berubah
                $('#start_date, #end_date').on('change', function() {
                    $('#filterForm').submit();
                });
            });

            // Fungsi reload table dan inisialisasi ulang DataTable
            function reloadTable() {
                $("#TableContainer").load(location.href + " #TableContainer > *", function() {
                    $('#myTable').DataTable().destroy();
                    $('#myTable').DataTable({
                        "columnDefs": [{
                                "orderable": false,
                                "targets": 6
                            },
                            {
                                "searchable": false,
                                "targets": 6
                            },
                            {
                                "targets": 0,
                                "width": "100px",
                                "className": "dt-center"
                            },
                            {
                                "targets": [1, 2, 3, 4, 5, 6],
                                "width": "120px"
                            }
                        ],
                        "order": [
                            [0, 'asc']
                        ],
                        "responsive": true
                    });
                });
            }
        </script>
    @endpush
@endsection
