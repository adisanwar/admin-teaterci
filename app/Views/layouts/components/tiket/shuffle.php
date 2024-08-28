<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Shuffle Data</h1>
    </div>

    <?php if (session()->getFlashData('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashData('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashData('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashData('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>


    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="shuffle-tab" data-toggle="tab" href="#shuffle" role="tab" aria-controls="shuffle" aria-selected="true">Hasil Pengacakan</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tmp-tickets-tab" data-toggle="tab" href="#tmp-tickets" role="tab" aria-controls="tmp-tickets" aria-selected="false">Data Tmp Tickets</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="myTabContent">
        <!-- Shuffle Tab (Tab pertama untuk hasil pengacakan) -->
        <div class="tab-pane fade" id="tmp-tickets" role="tabpanel" aria-labelledby="tmp-tickets-tab">
        
            <div class="card shadow mb-4 mt-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ticket ID</th>
                                    <th>Contact ID</th>
                                    <th>Shuffled At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($shuffle) && is_array($shuffle)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($shuffle as $item): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($item['ticketId']) ?></td>
                                            <td><?= esc($item['contactId']) ?></td>
                                            <td><?= date('D d F Y H:i:s', strtotime($item['shuffledAt'])) ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">View</a>
                                                <a href="<?= base_url('/shuffle/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this shuffle?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">No shuffled tickets available.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

     

        <!-- Tmp Tickets Tab (Tab kedua untuk tmp tickets) -->
<div class="tab-pane fade show active" id="shuffle" role="tabpanel" aria-labelledby="shuffle-tab">
    <div class="card shadow mb-4 mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <!-- Form untuk input angka dan tombol Acak Tiket -->
            <form id="shuffleForm" action="<?= base_url('/shuffle/process') ?>" method="post" class="form-inline">
                <div class="form-group mb-2">
                    <label for="shuffleCount" class="sr-only">Jumlah Tiket</label>
                    <input type="number" class="form-control" id="shuffleCount" name="shuffleCount" placeholder="Jumlah" required style="width: 100px; margin-right: 10px;">
                </div>
                <button type="submit" class="btn btn-sm btn-primary mb-2">Acak Tiket</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Seat Number</th>
                            <th>Purchase Date</th>
                            <th>Status</th>
                            <th>Show ID</th>
                            <th>Contact ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($tmpTickets) && is_array($tmpTickets)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($tmpTickets as $ticket): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($ticket['seatNumber']) ?? 'N/A' ?></td>
                                    <td><?= date('D d F Y H:i:s', strtotime($ticket['purchaseDate'])) ?></td>
                                    <td><?= esc($ticket['status']) ?? 'N/A' ?></td>
                                    <td><?= esc($ticket['showId']) ?></td>
                                    <td><?= esc($ticket['contactId']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No tmp tickets available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



    </div>
</div>
<script>
    // Validasi form sebelum submit
    document.getElementById('shuffleForm').addEventListener('submit', function(e) {
        var shuffleCount = document.getElementById('shuffleCount').value;
        if (!shuffleCount || shuffleCount <= 0) {
            e.preventDefault(); // Cegah form dari pengiriman
            alert('Jumlah tiket untuk diacak harus diisi dengan angka yang valid.');
        }
    });
</script>
<script>
    // Validasi form sebelum submit
    document.getElementById('shuffleForm').addEventListener('submit', function(e) {
        var shuffleCount = document.getElementById('shuffleCount').value;
        if (!shuffleCount || shuffleCount <= 0) {
            e.preventDefault(); // Cegah form dari pengiriman
            alert('Jumlah tiket untuk diacak harus diisi dengan angka yang valid.');
        }
    });
</script>



    </div>


</div>

<!-- /.container-fluid -->
<script>
    $(document).ready(function() {
        $('#dataTable1').DataTable();
        $('#dataTable2').DataTable();
    });
</script>
<?= $this->endSection() ?>
