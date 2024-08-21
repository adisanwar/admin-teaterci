<link rel="stylesheet" href="<?= base_url('assets/css/sb-admin-2.min.css') ?>">

<head>
    <style type="text/css">
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }
    </style>
</head>
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="<?php echo base_url('assets\img\unlock.svg')?>" class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
            <?php
                if (session()->getFlashdata('message')) {
                ?>
                    <div class="alert alert-info">
                        <?= session()->getFlashdata('message') ?>
                    </div>
                <?php } ?>
                <form method="post" action="<?= base_url('login/auth');?>" >
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input type="text"  class="form-control form-control-lg" name="username" />
                        <label class="form-label" >Username</label>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <input type="password"  class="form-control form-control-lg" name="password" required=""/>
                        <label class="form-label" >Password</label>
                    </div>

                    <div class="d-flex justify-content-around align-items-center mb-4">
                        <!-- Checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
                            <label class="form-check-label" for="form1Example3"> Remember me </label>
                        </div>
                        <a href="#!">Forgot password?</a>
                    </div>

                    <!-- Submit button -->
                    
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</section>