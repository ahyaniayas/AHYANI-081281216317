<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
  protected $table = 'm_user';
  protected $primaryKey = 'username';
  protected $allowedFields = ['username', 'password', 'nama', 'divisi'];
}