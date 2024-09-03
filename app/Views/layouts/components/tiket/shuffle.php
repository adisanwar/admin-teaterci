<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Shuffle Data</h1>
    </div>

    <!-- Area untuk menampilkan pesan -->
    <div id="message"></div>

    <!-- Form untuk input angka dan tombol Acak Tiket -->
    <form id="shuffleForm" class="form-inline mb-4">
        <div class="form-group mr-2">
            <label for="shuffleCount" class="sr-only">Jumlah Tiket</label>
            <input type="number" class="form-control" id="shuffleCount" name="shuffleCount" placeholder="Jumlah" required style="width: 150px;">
        </div>
        <button type="submit" class="btn btn-primary">Acak Tiket</button>
    </form>

    <!-- Tabel untuk menampilkan hasil pengacakan -->
    <div class="table-responsive">
        <table class="table table-bordered" id="shuffleTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ticket ID</th>
                    <th>Contact ID</th>
                    <th>Shuffled At</th>
                </tr>
            </thead>
            <tbody id="shuffleTableBody">
                <tr>
                    <td colspan="4" class="text-center">No tickets available.</td>
                </tr>
            </tbody>
        </table>
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
                if (response.status === 'success') {
                    displayMessage(response.message, 'success');
                    updateTable(response.data);
                } else {
                    displayMessage(response.message, 'danger');
                    clearTable();
                }
            },
            error: function(xhr, status, error) {
                $('#shuffleForm button[type="submit"]').prop('disabled', false);
                console.error(error);
                displayMessage('Terjadi kesalahan saat memproses permintaan.', 'danger');
                clearTable();
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
                    // '<td>' + (index + 1) + '</td>' +
                    '<td>' + escapeHtml(ticket.ticketId) + '</td>' +
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

<?= $this->endSection() ?>
