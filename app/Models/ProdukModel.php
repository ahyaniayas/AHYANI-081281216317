<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
  protected $table = 'm_produk';
  protected $primaryKey = 'id';
  protected $allowedFields = ['id', 'nama', 'satuan', 'harga'];
}