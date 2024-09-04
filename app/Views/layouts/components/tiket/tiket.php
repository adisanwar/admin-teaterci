<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tiket data</h1>
    </div>

    <?php if (session()->getFlashData('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashData('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

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

<!-- /.container-fluid -->
<script>
    // Panggil plugin dataTables jQuery
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<?= $this->endSection() ?>
