<?php

namespace App\Models;

use CodeIgniter\Model;

class FakturDtlModel extends Model
{
  protected $table = 't_faktur_dtl';
  protected $primaryKey = 'id';
  protected $allowedFields = ['no', 'produk_id', 'produk_nama', 'produk_satuan', 'produk_harga', 'jumlah'];
}