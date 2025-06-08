<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />

    @stack('styles')
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar" class="bg-light">
        @include('app_layouts.sidebar')
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="p-4 w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <button class="btn btn-primary" id="sidebarToggle">☰</button>

            <x-back-button>Back</x-back-button>
        </nav>

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables & Export Buttons -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Export buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('sidebarToggle');

    // Sembunyikan sidebar default di mobile saat halaman load
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth < 768) {
            sidebar.classList.add('d-none');
        }
    });

    // Toggle sidebar visibility on button click
    toggleButton.addEventListener('click', function (e) {
        e.stopPropagation();
        sidebar.classList.toggle('d-none');
    });

    // Close sidebar on outside click for mobile only
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 768) {
            if (!sidebar.contains(e.target) && !toggleButton.contains(e.target)) {
                sidebar.classList.add('d-none');
            }
        }
    });

    // Prevent sidebar from closing when clicked inside
    sidebar.addEventListener('click', function (e) {
        e.stopPropagation();
    });
</script>

<script>
    $(document).ready(function() {
        $('.datatable-export').DataTable({
            dom: `
                <'d-flex flex-wrap align-items-center justify-content-between mb-2'
                    <'lengthMenu mb-2 me-2 mb-md-0'l>
                    <'dt-buttons btn-group flex-wrap mb-2 mb-md-0'B>
                    <'searchBox ms-auto'f>
                >
                tip
            `,
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                lengthMenu: "Show _MENU_ rows",
                search: "Search:",
                zeroRecords: "No matching records found",
                info: "Showing _START_ to _END_ of _TOTAL_ rows",
                infoEmpty: "No rows available",
                infoFiltered: "(filtered from _MAX_ total rows)",
                paginate: {
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    });
</script>


@stack('scripts')
</body>
</html>
