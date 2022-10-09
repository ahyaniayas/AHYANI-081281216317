<?= $this->extend('templates/admin') ?>

<?= $this->section('content-admin') ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    
    <?php if(isset($validation) or isset($message)){ ?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <?= (isset($validation)? formValidationError($validation->getErrors()): '').(isset($message)? $message: '') ?>
    </div>
    <?php } ?>

    <div class="row">
      <div class="col-lg-3"></div>
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <form action="<?= base_url($processURL) ?>" method="post">
              <div class="form-group mb-3">
                <label>Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama" value="<?= !empty($nama)? $nama: '' ?>" />
              </div>
              <div class="form-group mb-3">
                <label>Divisi</label>
                <input type="text" name="divisi" id="divisi" class="form-control" placeholder="Masukkan Divisi" value="<?= !empty($divisi)? $divisi: '' ?>" />
              </div>
              <div class="form-group mb-3">
                <label>Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan Username" value="<?= !empty($username)? $username: '' ?>" <?= ($flagEdit==1)? "readonly": '' ?> />
              </div>
              <div class="form-group mb-3">
                <label>Password</label>
                <input type="text" name="password" id="password" class="form-control" placeholder="Masukkan Password" value="<?= !empty($password)? $password: '' ?>" />
              </div>
              <div class="row">
                <div class="col-6">
                  <a href="<?= base_url($segment1.'/'.$segment2) ?>" class="btn btn-secondary btn-block">Kembali</a>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <button type="submit" class="<?= $buttonClass ?> btn-block"><?= $buttonLable ?></button>
                </div>
                <!-- /.col -->
              </div>
            </form>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

      </div>
      <div class="col-lg-3"></div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>