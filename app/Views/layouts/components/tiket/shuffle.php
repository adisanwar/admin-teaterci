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
            <div class="card shadow mb-4">
                <!-- <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Acak Tiket</h5>
            </div> -->
                <div class="card-body">
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
                        <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contact ID</th>
                                </tr>
                            </thead>
                            <tbody id="tmpTicketsTableBody">
                                <tr>
                                    <td colspan="3" class="text-center">No tickets available.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="message"></div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk menangani AJAX dan memperbarui tabel -->
<script src="<?= base_url('assets/template/jquery/jquery.min.js'); ?>"></script>
<!-- base_url('assets/template/jquery/jquery.min.js'); -->
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
                data: {
                    shuffleCount: shuffleCount
                },
                dataType: 'json',
                beforeSend: function() {
                    // Optional: tampilkan loader atau disable tombol
                    $('#shuffleForm button[type="submit"]').prop('disabled', true);
                    displayMessage('Sedang memproses...', 'info');
                },
                success: function(response) {
                    $('#shuffleForm button[type="submit"]').prop('disabled', false);
                    if (response.data && response.data.length > 0) {
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
            var tableBody = $('#tmpTicketsTableBody');
            tableBody.empty(); // Kosongkan isi tabel

            if (data.length > 0) {
                $.each(data, function(index, ticket) {
                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + escapeHtml(ticket.name) + '</td>' +
                        '<td>' + escapeHtml(ticket.contactId) + '</td>' +
                        '</tr>';
                    tableBody.append(row);
                });

                // Inisialisasi DataTable jika belum diinisialisasi sebelumnya
                if (!$.fn.DataTable.isDataTable('#dataTable2')) {
                    $('#dataTable2').DataTable({
                        "autoWidth": false
                    });
                }
            } else {
                clearTable();
            }
        }

        // Fungsi untuk mengosongkan tabel
        function clearTable() {
            var tableBody = $('#tmpTicketsTableBody');
            tableBody.empty();
            tableBody.append('<tr><td colspan="3" class="text-center">No tickets available.</td></tr>');

            // Hancurkan DataTable jika sudah diinisialisasi sebelumnya
            if ($.fn.DataTable.isDataTable('#dataTable2')) {
                $('#dataTable2').DataTable().clear().destroy();
            }
        }

        // Fungsi untuk mencegah XSS
        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }
    });
</script>

<!-- DataTable Initialization -->
<script>
    $(document).ready(function() {
        $('#dataTable1').DataTable();
        $('#dataTable2').DataTable();
    });
</script>
<?= $this->endSection() ?>