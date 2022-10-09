<?php

namespace App\Models;

use CodeIgniter\Model;

class FakturHdrModel extends Model
{
  protected $table = 't_faktur_hdr';
  protected $primaryKey = 'no';
  protected $allowedFields = ['no', 'tanggal', 'supplier_id', 'supplier_nama', 'supplier_alamat', 'supplier_kontak'];
}