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
                  <label>Supplier</label>
                </div>
                <div class="col-6 text-right">
                  <a href="<?= base_url('master/supplier/add') ?>" class="btn btn-success" title="Tambah">Tambah</a>
                </div>
              </div>
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Kontak</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach($suppliers as $isiSuppliers){ ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $isiSuppliers['nama'] ?></td>
                  <td><?= $isiSuppliers['alamat'] ?></td>
                  <td><?= $isiSuppliers['kontak'] ?></td>
                  <td class="text-center">
                    <a href="<?= base_url('master/supplier/edit/'.$isiSuppliers['id']) ?>" class="btn btn-primary mb-1" title="Edit">Edit</a>
                    <a href="<?= base_url('master/supplier/hapus/'.$isiSuppliers['id']) ?>" class="btn btn-danger mb-1" title="Hapus">Hapus</
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