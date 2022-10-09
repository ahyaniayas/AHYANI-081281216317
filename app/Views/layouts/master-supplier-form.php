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
                <label>Alamat</label>
                <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat" value="<?= !empty($alamat)? $alamat: '' ?>" />
              </div>
              <div class="form-group mb-3">
                <label>Kontak</label>
                <input type="text" name="kontak" id="kontak" class="form-control" placeholder="Masukkan Kontak" value="<?= !empty($kontak)? $kontak: '' ?>" />
              </div>
              <div class="row">
                <div class="col-6">
                  <a href="<?= base_url($segment1.'/'.$segment2) ?>" class="btn btn-secondary btn-block">Kembali</a>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <input type="hidden" name="id" value="<?= !empty($id)? $id: '' ?>" />
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