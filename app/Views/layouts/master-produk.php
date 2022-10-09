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
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="form-group mb-3">
              <div class="row">
                <div class="col-6">
                  <label>Produk</label>
                </div>
                <div class="col-6 text-right">
                  <a href="<?= base_url('master/produk/add') ?>" class="btn btn-success" title="Tambah">Tambah</a>
                </div>
              </div>
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Satuan</th>
                  <th>Harga</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach($produks as $isiProduks){ ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $isiProduks['id'] ?></td>
                  <td><?= $isiProduks['nama'] ?></td>
                  <td><?= $isiProduks['satuan'] ?></td>
                  <td><?= number_format($isiProduks['harga']) ?></td>
                  <td class="text-center">
                    <a href="<?= base_url('master/produk/edit/'.$isiProduks['id']) ?>" class="btn btn-primary mb-1" title="Edit">Edit</a>
                    <a href="<?= base_url('master/produk/hapus/'.$isiProduks['id']) ?>" class="btn btn-danger mb-1" title="Hapus">Hapus</
                  </td>
                </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>