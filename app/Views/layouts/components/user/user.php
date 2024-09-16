<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>
<!-- Mulai Konten Halaman -->
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Data</h1>
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

    <!-- Tabel Data Users -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <a href="#" data-target="#tambahUser" data-toggle="modal" class="btn btn-sm btn-primary">Tambah User</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($users) && is_array($users)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($user['name']) ?></td>
                                    <td><?= esc($user['username']) ?></td>
                                    <td><?= esc($user['isAdmin'] ? 'Admin' : 'User') ?></td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#editUser<?= esc($user['username']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="<?= base_url('/users/delete/' . esc($user['username'])) ?>" method="post" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete user <?= esc($user['username']); ?>?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No users available.</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <?php foreach ($users as $user) : ?>
        <div class="modal fade" id="editUser<?= esc($user['username']); ?>" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url('/users/update/' . esc($user['username'])) ?>" method="post">
                            <input type="hidden" name="_method" value="PATCH">
                            <?= csrf_field() ?>

                            <div class="form-group">
                                <label for="name" class="col-form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= esc($user['name']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="username" class="col-form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= esc($user['username']) ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password">
                            </div>

                            <div class="form-group">
                                <label for="role" class="col-form-label">Role</label>
                                <select class="form-control" id="role" name="isAdmin">
                                    <option value="1" <?= $user['isAdmin'] ? 'selected' : '' ?>>Admin</option>
                                    <option value="0" <?= !$user['isAdmin'] ? 'selected' : '' ?>>User</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>


    <!-- Modal Tambah User -->
    <div class="modal fade" id="tambahUser" tabindex="-1" role="dialog" aria-labelledby="tambahUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahUserLabel">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('/users/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="name" class="col-form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label">Password</label>
                            <input type="text" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group">
                            <label for="role" class="col-form-label">Role</label>
                            <select class="form-control" id="role" name="isAdmin">
                                <option value="1">Admin</option>
                                <option value="0">User</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tambah User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Panggil plugin dataTables jQuery
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <?= $this->endSection() ?>