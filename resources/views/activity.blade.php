<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Yajra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Activities List</h2>
        <table class="table table-bordered" id="activityTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Aktivitas</th>
                    <th>Masalah</th>
                    <th>Solusi</th>
                    <th>Status</th>
                    <th>Informasi</th>
                </tr>
            </thead>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#activityTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('activities.get') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_aktivitas', name: 'nama_aktivitas' },
                    { data: 'masalah', name: 'description' },
                    { data: 'solusi', name: 'status' },
                    { data: 'status_id', name: 'status_id', orderable: false, searchable: false },
                    { data: 'information', name: 'information'},
                ]
            });
        });
    </script>
</body>
</html>