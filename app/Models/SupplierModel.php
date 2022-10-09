<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
  protected $table = 'm_supplier';
  protected $primaryKey = 'id';
  protected $allowedFields = ['nama', 'alamat', 'kontak'];
}