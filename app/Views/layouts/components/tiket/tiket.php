<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tiket data</h1>
    </div>
    <!-- Area untuk menampilkan pesan -->
    <div id="message"></div>
<?php if (session()->getFlashData('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashData('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <!-- Form untuk input angka dan tombol Acak Tiket -->
    <form id="shuffleForm" class="form-inline mb-4">
        <div class="form-group mr-2">
            <label for="shuffleCount" class="sr-only">Jumlah Tiket</label>
            <input type="number" class="form-control" id="shuffleCount" name="shuffleCount" placeholder="Jumlah" required style="width: 150px;">
        </div>
        <button type="submit" class="btn btn-primary">Acak Tiket</button>
    </form>

    

    <!-- Tabel Data Tiket -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Seat Number</th>
                            <th>Purchase Date</th>
                            <th>Status</th>
                            <th>Show</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($tickets) && is_array($tickets)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $ticket['seatNumber'] ?? 'N/A' ?></td>
                                <td><?= date('D d F Y H:i:s', strtotime($ticket['purchaseDate'])) ?></td>
                                <td><?= $ticket['status'] ?? 'N/A' ?></td>
                                <td><?= $ticket['show']['title'] ?></td>
                                <td><?= $ticket['contact']['fullname'] ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">View</a>
                                    <a href="<?= base_url('/ticket/delete/' . $ticket['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No tickets available.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery untuk AJAX dan DataTable -->
<script src="<?= base_url('assets/template/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/template/datatables/jquery.dataTables.min.js'); ?>"></script>

<script>
$(document).ready(function() {
    $('#shuffleForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman form secara default

        var shuffleCount = $('#shuffleCount').val();
        if (!shuffleCount || shuffleCount <= 0) {
            displayMessage('Jumlah tiket untuk diacak harus diisi dengan angka yang valid.', 'danger');
            return;
        }

        // Kirim data via AJAX
        $.ajax({
            url: '<?= base_url('/shuffle/process') ?>', // Sesuaikan dengan route Anda
            type: 'POST',
            data: { shuffleCount: shuffleCount },
            dataType: 'json',
            beforeSend: function() {
                $('#shuffleForm button[type="submit"]').prop('disabled', true);
                displayMessage('Sedang memproses...', 'info');
            },
            success: function(response) {
                $('#shuffleForm button[type="submit"]').prop('disabled', false);

                // Memeriksa apakah respons API mengandung status sukses atau error
                if (response.status === 'success') {
                    displayMessage(response.message || 'Tiket berhasil diacak!', 'success');
                    updateTable(response.data); // Memperbarui tabel jika sukses
                } else {
                    displayMessage(response.message || 'Terjadi kesalahan saat pengacakan tiket.', 'danger');
                    clearTable(); // Kosongkan tabel jika gagal
                }
            },
            error: function(xhr, status, error) {
                $('#shuffleForm button[type="submit"]').prop('disabled', false);
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat memproses permintaan.';
                displayMessage(errorMessage, 'danger');
                clearTable(); // Kosongkan tabel jika ada error
            }
        });
    });

    // Fungsi untuk menampilkan pesan
    function displayMessage(message, type) {
        var messageHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>';
        $('#message').html(messageHtml);
    }

    // Fungsi untuk memperbarui tabel dengan data baru
    function updateTable(data) {
        var tableBody = $('#shuffleTableBody');
        tableBody.empty(); // Kosongkan isi tabel

        if (data.length > 0) {
            $.each(data, function(index, ticket) {
                var row = '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + escapeHtml(ticket.contact.fullname) + '</td>' +
                    '<td>' + escapeHtml(ticket.contactId) + '</td>' +
                    '<td>' + new Date(ticket.shuffledAt).toLocaleString() + '</td>' +
                    '</tr>';
                tableBody.append(row);
            });

            // Reinitialize DataTable jika diperlukan
            if ($.fn.DataTable.isDataTable('#shuffleTable')) {
                $('#shuffleTable').DataTable().destroy();
            }
            $('#shuffleTable').DataTable({
                "autoWidth": false
            });
        } else {
            clearTable();
        }
    }

    // Fungsi untuk mengosongkan tabel
    function clearTable() {
        var tableBody = $('#shuffleTableBody');
        tableBody.empty();
        tableBody.append('<tr><td colspan="4" class="text-center">No tickets available.</td></tr>');
    }

    // Fungsi untuk mencegah XSS
    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }
});
</script>
<!-- /.container-fluid -->
<script>
    // Panggil plugin dataTables jQuery
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<?= $this->endSection() ?>
