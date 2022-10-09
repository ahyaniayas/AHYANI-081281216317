<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login eFaktur - PT Bhinneka Sangkuriang Transport</title>
  <meta content="description" name="description">
  <meta content="keywords" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url('assets/admin/dist/img/AdminLTELogo.png') ?>" rel="icon">
  <link href="<?= base_url('assets/admin/dist/img/AdminLTELogo.png') ?>" rel="apple-touch-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/admin/dist/css/adminlte.min.css') ?>">
</head>
<body class="hold-transition login-page">

  <div class="login-box">

    <?php if(isset($validation) or isset($message)){ ?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <?= (isset($validation)? formValidationError($validation->getErrors()): '').(isset($message)? $message: '') ?>
    </div>
    <?php } ?>

    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <h1 class="m-0"><a href="#" class="h1" onclick="return false"><b>Admin</b></a></h1>
        <p class="m-0">eFaktur - PT Bhinneka Sangkuriang Transport</p>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Silahkan masuk untuk mengubah konten</p>

        <form action="<?= base_url('login-process') ?>" method="post">
          <div class="input-group mb-3">
            <input type="text" name="username" id="username" class="form-control" value="<?= isset($formData['username'])? $formData['username']: '' ?>" autocomplete="off" placeholder="Masukkan Username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" id="password" class="form-control" value="<?= isset($formData['password'])? $formData['password']: '' ?>" placeholder="Masukkan Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="show-password" onclick="showPassword(this.id)">
                <label for="show-password">
                  Lihat Password
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url('assets/admin/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('assets/admin/dist/js/adminlte.min.js') ?>"></script>

  <script>
    function showPassword(ini){
      var isi = $('#'+ini+':checked').val();
      if(isi=='on'){
        $('#password').attr('type', 'text')
      }else{
        $('#password').attr('type', 'password')
      }
    }

    $(document).ready(function(){
      $("#username").focus();
    })
  </script>
</body>
</html>
