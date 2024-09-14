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
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?= session()->getFlashData('success') ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
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
                                    <!-- Button to trigger the modal -->
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#ticketModal<?= $ticket['id'] ?>">View</button>

                                    <!-- Modal for each ticket -->
                                    <div class="modal fade" id="ticketModal<?= $ticket['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel<?= $ticket['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ticketModalLabel<?= $ticket['id'] ?>">Ticket Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Seat Number:</strong> <?= $ticket['seatNumber'] ?? 'N/A' ?></p>
                                                    <p><strong>Purchase Date:</strong> <?= date('D d F Y H:i:s', strtotime($ticket['purchaseDate'])) ?></p>
                                                    <p><strong>Status:</strong> <?= $ticket['status'] ?? 'N/A' ?></p>
                                                    <p><strong>Show:</strong> <?= $ticket['show']['title'] ?></p>
                                                    <p><strong>Contact:</strong> <?= $ticket['contact']['fullname'] ?></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable();

    // Event untuk tombol shuffle (acak tiket)
    $('#shuffleForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman form secara default

        var shuffleCount = $('#shuffleCount').val();
        if (!shuffleCount || shuffleCount <= 0) {
            displayMessage('Jumlah tiket untuk diacak harus diisi dengan angka yang valid.', 'error');
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
                    // SweetAlert for success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Tiket berhasil diacak!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Reload the page when the user clicks "OK"
                            location.reload();
                        }
                    });
                } else {
                    displayMessage(response.message || 'Terjadi kesalahan saat pengacakan tiket.', 'error');
                }
            },
            error: function(xhr, status, error) {
                $('#shuffleForm button[type="submit"]').prop('disabled', false);
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat memproses permintaan.';
                displayMessage(errorMessage, 'error');
            }
        });
    });

    // Fungsi untuk menampilkan pesan menggunakan SweetAlert
    function displayMessage(message, type) {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    }
});
</script>

<?= $this->endSection() ?>
