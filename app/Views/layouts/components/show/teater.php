<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Theater Data</h1>
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

    <!-- Tabel Data Theater -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <a href="#" data-target="#tambahTheater" data-toggle="modal" class="btn btn-sm btn-primary">Tambah Theater</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Theater</th>
                            <th>Foto</th>
                            <th>Lokasi</th>
                            <th>Kapasitas</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($theaters) && is_array($theaters)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($theaters as $theater): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $theater['name'] ?></td>
                                    <td>
                                        <?php if ($theater['photo']): ?>
                                            <?php
                                            // Remove 'src/img/' from the path
                                            $cleanedPhotoPath = str_replace('src/img/', '', $theater['photo']);
                                            ?>
                                            <img src="<?= esc($baseImgUrl . $cleanedPhotoPath) ?>" alt="Theater Photo" width="100">
                                        <?php else: ?>
                                            No Photo
                                        <?php endif; ?>
                                    </td>

                                    <td><?= $theater['location'] ?></td>
                                    <td><?= $theater['capacity'] ?></td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#editTheater<?= $theater['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="<?= base_url('/theaters/delete/' . $theater['id']) ?>" method="post" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete theater ID <?= $theater['id']; ?>?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No theaters available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit Theater -->
<?php foreach ($theaters as $theater): ?>
    <div class="modal fade" id="editTheater<?= $theater['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editTheaterLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTheaterLabel">Edit Theater</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add enctype="multipart/form-data" -->
                    <form action="<?= base_url('/theaters/update/' . $theater['id']) ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PATCH">
                        
                        <div class="form-group">
                            <label for="name" class="col-form-label">Nama Theater</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $theater['name'] ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="location" class="col-form-label">Lokasi</label>
                            <input type="text" class="form-control" id="location" name="location" value="<?= $theater['location'] ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="capacity" class="col-form-label">Kapasitas</label>
                            <input type="text" class="form-control" id="capacity" name="capacity" value="<?= $theater['capacity'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="photo" class="col-form-label">Foto</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                        </div>
                        <br>
                        
                        <?php if ($theater['photo']): ?>
                            <?php
                            // Remove 'src/img/' from the path
                            $cleanedPhotoPath = str_replace('src/img/', '', $theater['photo']);
                            ?>
                            <img src="<?= esc($baseImgUrl . $cleanedPhotoPath) ?>" alt="Theater Photo" width="400">
                        <?php else: ?>
                            No Photo
                        <?php endif; ?>
                        
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

    <!-- Modal Tambah Theater -->
    <div class="modal fade" id="tambahTheater" tabindex="-1" role="dialog" aria-labelledby="tambahTheaterLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahTheaterLabel">Tambah Theater</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('/theaters/store'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Nama Theater</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="location" class="col-form-label">Lokasi</label>
                            <input type="text" class="form-control" id="location" name="location">
                        </div>
                        <div class="form-group">
                            <label for="capacity" class="col-form-label">Kapasitas</label>
                            <input type="text" class="form-control" id="capacity" name="capacity">
                        </div>
                        <div class="form-group">
                            <label for="photo" class="col-form-label">Upload Foto</label>
                            <input type="file" class="form-control" id="photo" name="photo">
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