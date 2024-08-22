<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <!-- <i class="fas fa-laugh-wink"></i> -->
        </div>
        <div class="sidebar-brand-text mx-3">Admin Teater</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <!-- Divider -->
    <!-- <hr class="sidebar-divider"> -->
    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseShow" aria-expanded="true" aria-controls="collapseShow">
            <i class="fas fa-fw fa-table"></i>
            <span>Show</span>
        </a>
        <div id="collapseShow" class="collapse" aria-labelledby="headingShow" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url('show') ?>">Show</a>
                <a class="collapse-item" href="<?= base_url('theaters') ?>">Teater</a>
                <a class="collapse-item" href="<?= base_url('showtime') ?>">Jadwal</a>
            </div>
        </div>
    </li>
    <!-- <hr class="sidebar-divider"> -->
    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('orders') ?>">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Order</span></a>
    </li>
    <!-- <hr class="sidebar-divider"> -->
    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTicket" aria-expanded="true" aria-controls="collapseTicket">
            <i class="fas fa-fw fa-solid fa-ticket"></i>
            <span>Tiket</span>
        </a>
        <div id="collapseTicket" class="collapse" aria-labelledby="headingTicket" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url('tiket') ?>">History</a>
                <a class="collapse-item" href="#">Undi Tiket</a>
                <!-- <a class="collapse-item" href="#">Jadwal</a> -->
            </div>
        </div>
    </li>
    <!-- <hr class="sidebar-divider"> -->
    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('users') ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>