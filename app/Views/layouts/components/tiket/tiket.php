<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tiket Data</h1>
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
                    <tbody id="ticketTableBody">
                    <?php if (isset($tickets) && is_array($tickets)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr data-ticket-id="<?= $ticket['id'] ?>">
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

    // Fungsi untuk memperbarui tabel dengan data baru tanpa mengosongkan tabel
    function updateTable(data) {
        var tableBody = $('#ticketTableBody');
        var existingIds = [];

        // Ambil semua ID tiket yang sudah ada di tabel
        $('#ticketTableBody tr').each(function() {
            var ticketId = $(this).data('ticket-id');
            if (ticketId) {
                existingIds.push(parseInt(ticketId));
            }
        });

        if (data.length > 0) {
            $.each(data, function(index, ticket) {
                // Tambahkan hanya data baru yang belum ada di tabel
                if (!existingIds.includes(ticket.id)) {
                    var row = '<tr data-ticket-id="' + ticket.id + '">' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + (ticket.seatNumber ?? 'N/A') + '</td>' +
                        '<td>' + new Date(ticket.purchaseDate).toLocaleString() + '</td>' +
                        '<td>' + (ticket.status ?? 'N/A') + '</td>' +
                        '<td>' + escapeHtml(ticket.show.title) + '</td>' +
                        '<td>' + escapeHtml(ticket.contact.fullname) + '</td>' +
                        '<td>' +
                            '<a href="#" class="btn btn-sm btn-primary">View</a>' +
                            '<a href="<?= base_url('/ticket/delete/') ?>' + ticket.id + '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this ticket?\')">Delete</a>' +
                        '</td>' +
                        '</tr>';
                    tableBody.append(row);
                }
            });

            // Reinitialize DataTable jika diperlukan
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().draw();
            }
        }
    }

    // Fungsi untuk polling setiap 1 detik
    // function pollData() {
    //     $.ajax({
    //         url: '', // Sesuaikan dengan endpoint Anda untuk mendapatkan data tiket terbaru
    //         type: 'GET',
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.status === 'success') {
    //                 updateTable(response.data);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error fetching ticket data:', error);
    //         }
    //     });
    // }

    // Jalankan polling setiap 1 detik
    // setInterval(pollData, 1000); // Polling setiap 1000ms atau 1 detik

    // Fungsi untuk mencegah XSS
    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    // Event untuk tombol shuffle (acak tiket)
    $('#shuffleForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman form secara default

        var shuffleCount = $('#shuffleCount').val();
        if (!shuffleCount || shuffleCount <= 0) {
            displayMessage('Jumlah tiket untuk diacak harus diisi dengan angka yang valid.', 'danger');
            return;
        }

        // Kirim data via AJAX untuk melakukan proses shuffle
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
                    displayMessage(response.message || 'Tiket berhasil diacak!', 'success');
                    updateTable(response.data); // Perbarui tabel dengan hasil shuffle
                } else {
                    displayMessage(response.message || 'Terjadi kesalahan saat pengacakan tiket.', 'danger');
                }
            },
            error: function(xhr, status, error) {
                $('#shuffleForm button[type="submit"]').prop('disabled', false);
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat memproses permintaan.';
                displayMessage(errorMessage, 'danger');
            }
        });
    });
});
</script>

<!-- DataTables Initialization -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<?= $this->endSection() ?>
