<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Aktivitas IT</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- CSRF Token untuk AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">    

    <!-- Custom Styles -->
    <style>
        .table th {
            background-color: #003975;
            color: white;
            text-align: center;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .status-selesai { background-color: #28a745; color: white; }
        .status-pending { background-color: #ffc107; color: black; }
        .status-proses { background-color: #17a2b8; color: white; }
        .status-waiting { background-color: #adadad; color: white; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📋 Daftar Aktivitas IT</h2>

    <!-- Tabel Form Input -->
    <table class="table table-bordered" id="formTable">
        <thead>
            <tr>
                <th>Aktivitas</th>
                <th>Masalah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="formBody">
            <!-- Baris form akan ditambahkan di sini -->
        </tbody>
    </table>

    <!-- Tombol Tambah & Simpan -->
    <div class="d-flex justify-content-between mb-5">
        <button class="btn btn-primary" id="addRow">➕ Tambah Baris</button>
        <button class="btn btn-success" id="saveAll">💾 Simpan Semua</button>
    </div>

    <br>
    <br>
    <!-- Tabel Data -->
    <table class="table table-striped table-hover table-bordered mt-3" id="activityTable">
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

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        let table = $('#activityTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('activities.get') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_aktivitas', name: 'nama_aktivitas' },
                { data: 'masalah', name: 'description' },
                { data: 'solusi', name: 'solusi' },
                { 
                    data: 'status', 
                    name: 'status', 
                    render: function(data) {
                        if (data === 'SELESAI') {
                            return '<span class="status-badge status-selesai">Selesai</span>';
                        } else if (data === 'PENDING') {
                            return '<span class="status-badge status-pending">Pending</span>';
                        } else if (data === 'WAITING') {
                            return '<span class="status-badge status-waiting">Dalam Antrian</span>';
                        } else {
                            return '<span class="status-badge status-proses">Dalam Pengerjaan</span>';
                        }
                    }
                },
                { data: 'information', name: 'information' },
            ]
        });

        // Tambahkan baris baru di form input
        $('#addRow').click(function() {
            let newRow = `
                <tr>
                    <td><input type="text" class="form-control nama_aktivitas" required></td>
                    <td><input type="text" class="form-control masalah" required></td>
                    <td><button class="btn btn-danger removeRow">❌</button></td>
                </tr>
            `;
            $('#formBody').append(newRow);
        });

        // Hapus baris input
        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });

        // Simpan semua data dengan AJAX
        $('#saveAll').click(function() {
            let allData = [];
            $('#formBody tr').each(function() {
                let nama_aktivitas = $(this).find('.nama_aktivitas').val();
                let masalah = $(this).find('.masalah').val();
                if (nama_aktivitas && masalah) {
                    allData.push({
                        nama_aktivitas: nama_aktivitas,
                        masalah: masalah
                    });
                }
            });

            if (allData.length === 0) {
                alert("Tidak ada data yang bisa disimpan!");
                return;
            }

            $.ajax({
                url: "{{ route('activities.storeMultiple') }}",
                method: "POST",
                data: { data: allData, _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    table.ajax.reload();
                    $('#formBody').empty();
                    alert("Semua tugas berhasil ditambahkan!");
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        let errorMessage = Object.values(errors).map(error => error[0]).join("\n");
                        alert("Gagal menyimpan tugas:\n" + errorMessage);
                    } else {
                        alert("Gagal menyimpan tugas. Silakan coba lagi!");
                    }
                }
            });
        });
    });
</script>

</body>
</html>
