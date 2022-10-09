<?= $this->extend('templates/admin') ?>

<?= $this->section('content-admin') ?>
<?php 
$grand_total = 0;
?>
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
      <div class="<?= ($flagEdit==1 || $flagEdit==2)? 'col-lg-1': 'col-lg-4' ?>"></div>
      <div class="<?= ($flagEdit==1 || $flagEdit==2)? 'col-lg-10': 'col-lg-4' ?>">
        <div class="card">
          <div class="card-body">
            <form action="<?= base_url($processURL) ?>" method="post">
              <div class="form-group mb-3">
                <label>No. Faktur</label>
                <input type="text" name="no_faktur" id="no_faktur" class="form-control" placeholder="Masukkan No. Faktur" value="<?= !empty($no_faktur)? $no_faktur: '' ?>" <?= $flagEdit==1? 'readonly':'' ?> />
              </div>
              <div class="form-group mb-3">
                <label>Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control">
                  <option value="">--- Pilih Supplier ---</option>
                  <?php foreach($suppliers as $isiSuppliers){ ?>
                  <option value="<?= $isiSuppliers['id'] ?>" <?= $supplier_id==$isiSuppliers['id']? 'selected': '' ?>><?= $isiSuppliers['nama'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <?php if($flagEdit==1 || $flagEdit==2){ ?>
              <div class="form-group mb-3">
                <div class="row">
                  <div class="col-6">
                    <label>Detail</label>
                  </div>
                  <div class="col-6 text-right">
                    <?php if($flagEdit==1){ ?>
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal-tambah" title="Tambah">Tambah</a>
                    <?php } ?>
                  </div>
                </div>
                <table id="example2" class="table table-bordered table-hover table-striped">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>Produk ID</th>
                    <th>Nama</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $no=1; foreach($fakturdtl as $isiFakturdtl){ $grand_total += ($isiFakturdtl['produk_harga']*$isiFakturdtl['jumlah']); ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $isiFakturdtl['produk_id'] ?></td>
                    <td><?= $isiFakturdtl['produk_nama'] ?></td>
                    <td><?= $isiFakturdtl['produk_satuan'] ?></td>
                    <td><?= number_format($isiFakturdtl['produk_harga']) ?></td>
                    <td><?= $isiFakturdtl['jumlah'] ?></td>
                    <td><?= number_format($isiFakturdtl['produk_harga']*$isiFakturdtl['jumlah']) ?></td>
                    <td class="text-center">
                      <?php if($flagEdit==1){ ?>
                      <a href="#" class="btn btn-danger mb-1" onclick="openHapusModal('<?= $isiFakturdtl['id'] ?>')" title="Hapus">Hapus</a>
                      <?php } ?></td>
                  </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="form-group mb-3">
                <label>Grand Total</label>
                <input type="text" class="form-control" value="<?= number_format($grand_total) ?>" readonly />
              </div>
              <?php } ?>
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
      <div class="<?= ($flagEdit==1 || $flagEdit==2)? 'col-lg-1': 'col-lg-4' ?>"></div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- modals -->
    <?php if($flagEdit==1){ ?>
    <div class="modal fade" id="modal-tambah">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="<?= base_url('transaksi/efaktur/'.bin2hex($no_faktur).'/add-process') ?>" method="post">
            <div class="modal-header">
              <h6 class="modal-title"><b>Tambah Detail</b></h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group mb-3">
                <label>Produk</label>
                <select name="produk_id" id="produk_id" class="form-control">
                  <option value="">--- Pilih Produk ---</option>
                  <?php foreach($produks as $isiProduks){ ?>
                  <option value="<?= $isiProduks['id'] ?>"><?= $isiProduks['nama'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group mb-3">
                <label>Jumlah</label>
                <input type="text" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan Jumlah" value="<?= !empty($jumlah)? $jumlah: '' ?>" />
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
              <button type="submit" class="btn btn-primary">Tambah Detail</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <?php foreach($fakturdtl as $isiFakturdtl){ ?>
    <div class="modal fade" id="modal-hapus-<?= $isiFakturdtl['id'] ?>">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="<?= base_url('transaksi/efaktur/'.bin2hex($no_faktur).'/hapus-process') ?>" method="post">
            <div class="modal-header">
              <h6 class="modal-title"><b>Hapus <?= $isiFakturdtl['produk_id'].' '.$isiFakturdtl['produk_nama'].' '.$isiFakturdtl['jumlah'].$isiFakturdtl['produk_satuan'] ?>?</b></h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-footer justify-content-between">
              <input type="hidden" name="id" value="<?= $isiFakturdtl['id'] ?>" />
              <input type="hidden" name="no_faktur" value="<?= $isiFakturdtl['no'] ?>" />
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
              <button type="submit" class="btn btn-danger">Hapus Detail</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <?php } ?>
    <?php } ?>
    <!-- /.modal -->

  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
<script>
  function openHapusModal(id){
    $('#modal-hapus-'+id).modal('show')
  }
</script>
<?= $this->endSection() ?>