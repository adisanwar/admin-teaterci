<?= $this->extend('layouts/components/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Temporary Tickets</h1>
    </div>

    <?php if (session()->getFlashData('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashData('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Seat Number</th>
                            <th>Purchase Date</th>
                            <th>Contact Name</th>
                            <th>Shuffled At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($tmpTickets) && is_array($tmpTickets)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($tmpTickets as $ticket): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $ticket['ticket']['seatNumber'] ?? 'N/A' ?></td>
                                <td><?= date('D d F Y H:i:s', strtotime($ticket['ticket']['purchaseDate'])) ?></td>
                                <td><?= $ticket['contact']['fullname'] ?? 'N/A' ?></td>
                                <td><?= date('D d F Y H:i:s', strtotime($ticket['shuffledAt'])) ?></td>
                                <td>
                                    <!-- Button to trigger the modal -->
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#ticketModal<?= $ticket['id'] ?>">
                                        View
                                    </button>
                                    
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
                                                    <p><strong>Seat Number:</strong> <?= $ticket['ticket']['seatNumber'] ?? 'N/A' ?></p>
                                                    <p><strong>Purchase Date:</strong> <?= date('D d F Y H:i:s', strtotime($ticket['ticket']['purchaseDate'])) ?></p>
                                                    <p><strong>Contact Name:</strong> <?= $ticket['contact']['fullname'] ?? 'N/A' ?></p>
                                                    <p><strong>Shuffled At:</strong> <?= date('D d F Y H:i:s', strtotime($ticket['shuffledAt'])) ?></p>
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
                            <td colspan="7">No temporary tickets available.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize the DataTables jQuery plugin
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<?= $this->endSection() ?>
