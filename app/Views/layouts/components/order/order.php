<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order data</h1>
    </div>

    <?php
    if (session()->getFlashData('success')) {
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashData('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php
    }
    ?>

    <!-- Tabel Data Order -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <!-- <a href="" data-target="#tambahShow" data-toggle="modal" class="btn btn-sm btn-primary">Tambah Order</a> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Show Title</th>
                            <th>Show Description</th>
                            <th>Show Duration</th>
                            <th>Purchase Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($orders) && is_array($orders)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $order['orderId'] ?></td>
                                    <td><?= number_format($order['amount'], 0, ',', '.') ?></td>
                                    <td><?= ucfirst($order['status']) ?></td>
                                    <td><?= $order['ticket']['show']['title'] ?></td>
                                    <td><?= $order['ticket']['show']['description'] ?></td>
                                    <td><?= $order['ticket']['show']['duration'] ?></td>
                                    <td><?= date('D d F Y', strtotime($order['ticket']['purchaseDate'])) ?></td>
                                    <td>
                                        <a href="<?= $order['paymentUrl'] ?>" target="_blank" class="btn btn-md btn-primary">Pay</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">No orders available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>


                <!-- /.container-fluid -->
                <script>
                    // Panggil plugin dataTables jQuery
                    $(document).ready(function() {
                        $('#dataTable').DataTable();
                    });
                </script>
                <?= $this->endSection() ?>