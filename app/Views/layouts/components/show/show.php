<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Show data</h1>
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

    <!-- Tabel Data Customer -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <a href="" data-target="#tambahShow" data-toggle="modal" class="btn btn-sm btn-primary">New Show</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Foto</th>
                            <th>Description</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Theater</th>
                            <th>Show Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($shows) && is_array($shows)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($shows as $shw): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $shw['title'] ?></td>
                                    <td>
                                        <?php if ($shw['photo']): ?>
                                            <?php
                                            // Remove 'src/img/' from the path
                                            $cleanedPhotoPath = str_replace('src/img/', '', $shw['photo']);
                                            ?>
                                            <img src="<?= esc($baseImgUrl . $cleanedPhotoPath) ?>" alt="Show Photo" width="100">
                                        <?php else: ?>
                                            No Photo
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $shw['description'] ?></td>
                                    <td><?= $shw['duration'] ?></td>
                                    <td><?= $shw['price'] ?></td>
                                    <td><?= $shw['theater']['name'] ?></td>
                                    <td><?= date('D d F Y', strtotime($shw['showtime']['showDate'])) ?></td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#editShow<?= $shw['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="<?= base_url('/show/delete/' . $shw['id']) ?>" method="post" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete theater ID')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No shows available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>


                <!-- Modal Edit Show -->
                <?php foreach ($shows as $show) : ?>
                    <div class="modal fade" id="editShow<?= $show['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editShowLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editShowLabel">Edit Show</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?= base_url('/show/update/' . $show['id']) ?>" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <div class="form-group">
                                            <label for="title" class="col-form-label">Title</label>
                                            <input type="text" class="form-control" id="title" name="title" value="<?= $show['title'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="col-form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description"><?= $show['description'] ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration" class="col-form-label">Duration</label>
                                            <input type="text" class="form-control" id="duration" name="duration" value="<?= $show['duration'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="price" class="col-form-label">Price</label>
                                            <input type="number" class="form-control" id="price" name="price" value="<?= $show['price'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="price" class="col-form-label">Rating</label>
                                            <input type="text" class="form-control" id="rating" name="rating" value="<?= $show['rating'] ?>">
                                        </div>
                                        <div class="form-group">
                                        <label for="theaterId" class="col-form-label">Theater</label>
                                        <select class="form-control" id="theaterId" name="theaterId">
                                            <?php foreach ($theaters as $theater): ?>
                                                <option value="<?= $theater['id']; ?>"><?= $theater['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                        <div class="form-group">
                                        <label for="showtimeId" class="col-form-label">Show Date</label>
                                        <select class="form-control" id="showtimeId" name="showtimeId">
                                            <?php foreach ($showtimes as $showtime): ?>
                                                <option value="<?= $showtime['id']; ?>"><?= date('D Y-m-d', strtotime($showtime['showDate'])) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <label for="created_at"></label>
                                        <input type="date" id="created_at" name="created_at" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                        <div class="form-group" style="display: none;">
                                            <label for="updated_at"></label>
                                            <input type="date" id="updated_at" name="updated_at" value="<?= date('Y-m-d') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="photo" class="col-form-label">Foto</label>
                                            <input type="file" class="form-control" id="photo" name="photo">
                                        </div>
                                        <br>

                                        <?php if ($show['photo']): ?>
                                            <?php
                                            // Remove 'src/img/' from the path
                                            $cleanedPhotoPath = str_replace('src/img/', '', $show['photo']);
                                            ?>
                                            <img src="<?= esc($baseImgUrl . $cleanedPhotoPath) ?>" alt="Show Photo" width="400">
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

                <!-- Modal Tambah Show -->
                <div class="modal fade" id="tambahShow" tabindex="-1" role="dialog" aria-labelledby="tambahShowLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahShowLabel">Tambah Data Show</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"> 
                                <form action="<?= base_url('/show/store'); ?>" method="post"  enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="title" class="col-form-label">Title</label> 
                                        <input type="text" class="form-control" id="title" name="title">
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="duration" class="col-form-label">Duration</label>
                                        <input type="text" class="form-control" id="duration" name="duration">
                                    </div>
                                    <div class="form-group">
                                        <label for="duration" class="col-form-label">Rating</label>
                                        <input type="text" class="form-control" id="rating" name="rating">
                                    </div>
                                    <div class="form-group">
                                        <label for="price" class="col-form-label">Price</label>
                                        <input type="number" class="form-control" id="price" name="price">
                                    </div>
                                    <div class="form-group">
                                        <label for="theaterId" class="col-form-label">Theater</label>
                                        <select class="form-control" id="theaterId" name="theaterId">
                                            <?php foreach ($theaters as $theater): ?>
                                                <option value="<?= $theater['id']; ?>"><?= $theater['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="showtimeId" class="col-form-label">Show Date</label>
                                        <select class="form-control" id="showtimeId" name="showtimeId">
                                            <?php foreach ($showtimes as $showtime): ?>
                                                <option value="<?= $showtime['id']; ?>"><?= date('D Y-m-d', strtotime($showtime['showDate'])) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <label for="created_at"></label>
                                        <input type="date" id="created_at" name="created_at" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="photo" class="col-form-label"> Upload Foto</label>
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