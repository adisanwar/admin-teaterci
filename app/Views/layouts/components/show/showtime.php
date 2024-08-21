<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Show data</h1>
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

    <div class="card shadow mb-4">
        <div class="card-header">
            <a href="#" data-target="#tambahShowtime" data-toggle="modal" class="btn btn-sm btn-primary">Tambah Showtime</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Show Date</th>
                            <th>Show Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($showtimes) && is_array($showtimes)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($showtimes as $showtime): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('D d F Y', strtotime($showtime['showDate'])) ?></td>
                                    <td><?= $showtime['showTime'] ?></td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#editShowtime<?= $showtime['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="<?= base_url('/showtime/delete/' . $showtime['id']) ?>" method="post" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this showtime?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No showtimes available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit Showtime -->
    <?php foreach ($showtimes as $showtime): ?>
        <div class="modal fade" id="editShowtime<?= $showtime['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editShowtimeLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editShowtimeLabel">Edit Showtime</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url('/showtime/update/' . $showtime['id']) ?>" method="post">
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group">
                                <label for="showDate" class="col-form-label">Show Date</label>
                                <input type="date" class="form-control" id="showDate" name="showDate" value="<?= date('Y-m-d', strtotime($showtime['showDate'])) ?>">
                            </div>
                            <div class="form-group">
                                <label for="showTime" class="col-form-label">Show Time</label>
                                <input type="text" class="form-control" id="showTime" name="showTime" value="<?= $showtime['showTime'] ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal Tambah Showtime -->
    <div class="modal fade" id="tambahShowtime" tabindex="-1" role="dialog" aria-labelledby="tambahShowtimeLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahShowtimeLabel">Tambah Showtime</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('/showtime/store'); ?>" method="post">
                        <div class="form-group">
                            <label for="showDate" class="col-form-label">Show Date</label>
                            <input type="date" class="form-control" id="showDate" name="showDate">
                        </div>
                        <div class="form-group">
                            <label for="showTime" class="col-form-label">Show Time</label>
                            <input type="text" class="form-control" id="showTime" name="showTime">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
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