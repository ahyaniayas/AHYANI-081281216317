<?php

namespace App\Controllers;

use Config\Services;
use Dompdf\Dompdf;
use Dompdf\Options;

class HomeController extends BaseController
{
  protected $session;
  protected $uri;
  protected $encrypter;
  protected $userModel;
  protected $produkModel;
  protected $supplierModel;
  protected $fakturHdrModel;
  protected $fakturDtlModel;

  public function __construct()
  {
    helper(['Form_helper']);

    $this->session = Services::session();
    $this->uri = Services::uri();
    $this->encrypter = Services::encrypter();
    $this->userModel = new \App\Models\UserModel();
    $this->produkModel = new \App\Models\ProdukModel();
    $this->supplierModel = new \App\Models\SupplierModel();
    $this->fakturHdrModel = new \App\Models\FakturHdrModel();
    $this->fakturDtlModel = new \App\Models\FakturDtlModel();
  }

  public function index()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Dashboard";
      $data['segment1'] = "";
      $data['segment2'] = "";
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));
      return view('layouts/home', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function login()
  {
    $data['user'] = $this->userModel->find();
    $encrypt = bin2hex($this->encrypter->encrypt('ahyani'));
    $decrypt = $this->encrypter->decrypt(hex2bin($data['user'][0]['password']));

    $data['validation'] = $this->session->getFlashdata('validation');
    $data['message'] = $this->session->getFlashdata('message');
    $data['formData'] = $this->session->getFlashdata('formData');

    return view('layouts/login', $data);
  }

  public function loginProcess()
  {
    $validation = $this->validate([
      'username' => [
        'rules' => 'required|is_not_unique[m_user.username]',
        'errors' => [
          'required' => 'Kolom Username tidak boleh kosong.',
          'is_not_unique' => 'Username tidak terdaftar.'
        ]
      ],
      'password' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Kolom Password tidak boleh kosong.'
        ]
      ]
    ]);

    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $formData = [
      'username' => $username,
      'password' => $password
    ];

    if (!$validation) {
      $this->session->setFlashdata('validation', $this->validator);
      $this->session->setFlashdata('formData', $formData);
      return redirect()->to('login');
    } else {
      $getuser = $this->userModel->where('username', $username)->find();
      // if(password_verify($password, $getuser[0]['password'])){
      if ($this->encrypter->decrypt(hex2bin($getuser[0]['password'])) == $password) {
        $this->session->set('userdata', bin2hex($this->encrypter->encrypt(json_encode($getuser[0]))));
        return redirect()->to('')->with('message', "Selamat datang " . $getuser[0]['nama'] . " (" . $getuser[0]['divisi'] . ")");
      } else {
        $this->session->setFlashdata('message', 'Password yang anda masukkan salah.');
        $this->session->setFlashdata('formData', $formData);
        return redirect()->to('login');
      }
    }
  }

  public function logoutProcess()
  {
    $this->session->remove('userdata');
    $message = 'Berhasil keluar.';
    return redirect()->to('login')->with('message', $message);
  }

  public function user()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/User";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));
      $data['users'] = $this->userModel->findAll();
      return view('layouts/master-user', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function userAdd()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/User/Tambah";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 0;
      $data['buttonLable'] = 'Tambah User';
      $data['buttonClass'] = 'btn btn-success';
      $data['processURL'] = 'master/user/add-process';

      $data['username'] = $this->session->getFlashdata('username');
      $data['password'] = $this->session->getFlashdata('password');
      $data['nama'] = $this->session->getFlashdata('nama');
      $data['divisi'] = $this->session->getFlashdata('divisi');
      return view('layouts/master-user-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function userAddProcess()
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'username' => [
          'rules' => 'required|is_unique[m_user.username]',
          'errors' => [
            'required' => 'Kolom Username tidak boleh kosong.',
            'is_unique' => 'Username sudah terdaftar.'
          ]
        ],
        'password' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Password tidak boleh kosong.'
          ]
        ],
        'nama' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Nama tidak boleh kosong.'
          ]
        ],
        'divisi' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Divisi tidak boleh kosong.'
          ]
        ]
      ]);

      $username = $this->request->getPost('username');
      $password = $this->request->getPost('password');
      $nama = $this->request->getPost('nama');
      $divisi = $this->request->getPost('divisi');

      $formData = [
        'username' => $username,
        'password' => bin2hex($this->encrypter->encrypt($password)),
        'nama' => $nama,
        'divisi' => $divisi
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        $this->session->setFlashdata('username', $username);
        $this->session->setFlashdata('password', $password);
        $this->session->setFlashdata('nama', $nama);
        $this->session->setFlashdata('divisi', $divisi);
        return redirect()->to('master/user/add');
      } else {
        $this->userModel->insert($formData);
        return redirect()->to('master/user')->with('message', 'User berhasil ditambah.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function userEdit($username)
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/User/Edit";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 1;
      $data['buttonLable'] = 'Edit User';
      $data['buttonClass'] = 'btn btn-primary';
      $data['processURL'] = 'master/user/edit-process';

      $user = $this->userModel->find($username);
      $data['username'] = $user['username'];
      $data['password'] = $this->encrypter->decrypt(hex2bin($user['password']));
      $data['nama'] = $user['nama'];
      $data['divisi'] = $user['divisi'];
      return view('layouts/master-user-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function userEditProcess()
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'password' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Password tidak boleh kosong.'
          ]
        ],
        'nama' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Nama tidak boleh kosong.'
          ]
        ],
        'divisi' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Divisi tidak boleh kosong.'
          ]
        ]
      ]);

      $username = $this->request->getPost('username');
      $password = $this->request->getPost('password');
      $nama = $this->request->getPost('nama');
      $divisi = $this->request->getPost('divisi');

      $formData = [
        'password' => bin2hex($this->encrypter->encrypt($password)),
        'nama' => $nama,
        'divisi' => $divisi
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        return redirect()->to('master/user/edit/' . $username);
      } else {
        $this->userModel->update($username, $formData);
        return redirect()->to('master/user')->with('message', 'User berhasil diedit.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function userHapus($username)
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/User/Hapus";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 1;
      $data['buttonLable'] = 'Hapus User';
      $data['buttonClass'] = 'btn btn-danger';
      $data['processURL'] = 'master/user/hapus-process';

      $user = $this->userModel->find($username);
      $data['username'] = $user['username'];
      $data['password'] = $this->encrypter->decrypt(hex2bin($user['password']));
      $data['nama'] = $user['nama'];
      $data['divisi'] = $user['divisi'];
      return view('layouts/master-user-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function userHapusProcess()
  {
    if ($this->session->has('userdata')) {

      $username = $this->request->getPost('username');

      $this->userModel->delete($username);
      return redirect()->to('master/user')->with('message', 'User berhasil dihapus.');
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function produk()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/Produk";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));
      $data['produks'] = $this->produkModel->findAll();
      return view('layouts/master-produk', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function produkAdd()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/Produk/Tambah";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 0;
      $data['buttonLable'] = 'Tambah Produk';
      $data['buttonClass'] = 'btn btn-success';
      $data['processURL'] = 'master/produk/add-process';

      $data['id'] = $this->session->getFlashdata('id');
      $data['nama'] = $this->session->getFlashdata('nama');
      $data['satuan'] = $this->session->getFlashdata('satuan');
      $data['harga'] = $this->session->getFlashdata('harga');
      return view('layouts/master-produk-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function produkAddProcess()
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'id' => [
          'rules' => 'required|is_unique[m_produk.id]',
          'errors' => [
            'required' => 'Kolom ID tidak boleh kosong.',
            'is_unique' => 'ID sudah terdaftar.'
          ]
        ],
        'nama' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Nama tidak boleh kosong.'
          ]
        ],
        'satuan' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Satuan tidak boleh kosong.'
          ]
        ],
        'harga' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Harga tidak boleh kosong.'
          ]
        ]
      ]);

      $id = $this->request->getPost('id');
      $nama = $this->request->getPost('nama');
      $satuan = $this->request->getPost('satuan');
      $harga = $this->request->getPost('harga');

      $formData = [
        'id' => $id,
        'nama' => $nama,
        'satuan' => $satuan,
        'harga' => $harga
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        $this->session->setFlashdata('id', $id);
        $this->session->setFlashdata('nama', $nama);
        $this->session->setFlashdata('satuan', $satuan);
        $this->session->setFlashdata('harga', $harga);
        return redirect()->to('master/produk/add');
      } else {
        $this->produkModel->insert($formData);
        return redirect()->to('master/produk')->with('message', 'Produk berhasil ditambah.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function produkEdit($id)
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/Produk/Edit";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 1;
      $data['buttonLable'] = 'Edit Produk';
      $data['buttonClass'] = 'btn btn-primary';
      $data['processURL'] = 'master/produk/edit-process';

      $produk = $this->produkModel->find($id);
      $data['id'] = $produk['id'];
      $data['nama'] = $produk['nama'];
      $data['satuan'] = $produk['satuan'];
      $data['harga'] = $produk['harga'];
      return view('layouts/master-produk-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function produkEditProcess()
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'nama' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Nama tidak boleh kosong.'
          ]
        ],
        'satuan' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Satuan tidak boleh kosong.'
          ]
        ],
        'harga' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Harga tidak boleh kosong.'
          ]
        ]
      ]);

      $id = $this->request->getPost('id');
      $nama = $this->request->getPost('nama');
      $satuan = $this->request->getPost('satuan');
      $harga = $this->request->getPost('harga');

      $formData = [
        'nama' => $nama,
        'satuan' => $satuan,
        'harga' => $harga
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        return redirect()->to('master/produk/edit/' . $id);
      } else {
        $this->produkModel->update($id, $formData);
        return redirect()->to('master/produk')->with('message', 'Produk berhasil diedit.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function produkHapus($id)
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/Produk/Hapus";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 1;
      $data['buttonLable'] = 'Hapus Produk';
      $data['buttonClass'] = 'btn btn-danger';
      $data['processURL'] = 'master/produk/hapus-process';

      $produk = $this->produkModel->find($id);
      $data['id'] = $produk['id'];
      $data['nama'] = $produk['nama'];
      $data['satuan'] = $produk['satuan'];
      $data['harga'] = $produk['harga'];
      return view('layouts/master-produk-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function produkHapusProcess()
  {
    if ($this->session->has('userdata')) {

      $id = $this->request->getPost('id');

      $this->produkModel->delete($id);
      return redirect()->to('master/produk')->with('message', 'Produk berhasil dihapus.');
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function supplier()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/Supplier";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));
      $data['suppliers'] = $this->supplierModel->findAll();
      return view('layouts/master-supplier', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function supplierAdd()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/Supplier/Tambah";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 0;
      $data['buttonLable'] = 'Tambah Supplier';
      $data['buttonClass'] = 'btn btn-success';
      $data['processURL'] = 'master/supplier/add-process';

      $data['nama'] = $this->session->getFlashdata('nama');
      $data['alamat'] = $this->session->getFlashdata('alamat');
      $data['kontak'] = $this->session->getFlashdata('kontak');
      return view('layouts/master-supplier-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function supplierAddProcess()
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'nama' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Nama tidak boleh kosong.'
          ]
        ],
        'alamat' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Alamat tidak boleh kosong.'
          ]
        ],
        'kontak' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Kontak tidak boleh kosong.'
          ]
        ]
      ]);

      $nama = $this->request->getPost('nama');
      $alamat = $this->request->getPost('alamat');
      $kontak = $this->request->getPost('kontak');

      $formData = [
        'nama' => $nama,
        'alamat' => $alamat,
        'kontak' => $kontak
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        $this->session->setFlashdata('nama', $nama);
        $this->session->setFlashdata('alamat', $alamat);
        $this->session->setFlashdata('kontak', $kontak);
        return redirect()->to('master/supplier/add');
      } else {
        $this->supplierModel->insert($formData);
        return redirect()->to('master/supplier')->with('message', 'Supplier berhasil ditambah.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function supplierEdit($id)
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/Supplier/Edit";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 1;
      $data['buttonLable'] = 'Edit Supplier';
      $data['buttonClass'] = 'btn btn-primary';
      $data['processURL'] = 'master/supplier/edit-process';

      $supplier = $this->supplierModel->find($id);
      $data['id'] = $supplier['id'];
      $data['nama'] = $supplier['nama'];
      $data['alamat'] = $supplier['alamat'];
      $data['kontak'] = $supplier['kontak'];
      return view('layouts/master-supplier-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function supplierEditProcess()
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'nama' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Nama tidak boleh kosong.'
          ]
        ],
        'alamat' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Alamat tidak boleh kosong.'
          ]
        ],
        'kontak' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Kontak tidak boleh kosong.'
          ]
        ]
      ]);

      $id = $this->request->getPost('id');
      $nama = $this->request->getPost('nama');
      $alamat = $this->request->getPost('alamat');
      $kontak = $this->request->getPost('kontak');

      $formData = [
        'nama' => $nama,
        'alamat' => $alamat,
        'kontak' => $kontak
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        return redirect()->to('master/supplier/edit/' . $id);
      } else {
        $this->supplierModel->update($id, $formData);
        return redirect()->to('master/supplier')->with('message', 'Supplier berhasil diedit.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function supplierHapus($id)
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Master Data/Supplier/Hapus";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 1;
      $data['buttonLable'] = 'Hapus Supplier';
      $data['buttonClass'] = 'btn btn-danger';
      $data['processURL'] = 'master/supplier/hapus-process';

      $supplier = $this->supplierModel->find($id);
      $data['id'] = $supplier['id'];
      $data['nama'] = $supplier['nama'];
      $data['alamat'] = $supplier['alamat'];
      $data['kontak'] = $supplier['kontak'];
      return view('layouts/master-supplier-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function supplierHapusProcess()
  {
    if ($this->session->has('userdata')) {

      $id = $this->request->getPost('id');

      $this->supplierModel->delete($id);
      return redirect()->to('master/supplier')->with('message', 'Supplier berhasil dihapus.');
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efaktur()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Transaksi/eFaktur";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));
      $data['fakturs'] = $this->fakturHdrModel->findAll();
      return view('layouts/transaksi-efaktur', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturAdd()
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Transaksi/eFaktur/Tambah";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 0;
      $data['buttonLable'] = 'Tambah eFaktur';
      $data['buttonClass'] = 'btn btn-success';
      $data['processURL'] = 'transaksi/efaktur/add-process';

      $data['no_faktur'] = $this->session->getFlashdata('no_faktur');
      $data['supplier_id'] = $this->session->getFlashdata('supplier_id');

      $data['suppliers'] = $this->supplierModel->findAll();
      return view('layouts/transaksi-efaktur-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturAddProcess()
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'no_faktur' => [
          'rules' => 'required|is_unique[t_faktur_hdr.no]',
          'errors' => [
            'required' => 'Kolom No. Faktur tidak boleh kosong.',
            'is_unique' => 'No. Faktur sudah terdaftar.'
          ]
        ],
        'supplier_id' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Supplier tidak boleh kosong.'
          ]
        ],
      ]);

      $no_faktur = $this->request->getPost('no_faktur');
      $supplier_id = $this->request->getPost('supplier_id');

      $formData = [
        'no' => $no_faktur,
        'supplier_id' => $supplier_id
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        $this->session->setFlashdata('no_faktur', $no_faktur);
        $this->session->setFlashdata('supplier_id', $supplier_id);
        return redirect()->to('transaksi/efaktur/add');
      } else {
        $supplier = $this->supplierModel->find($supplier_id);
        $supplier_nama = $supplier['nama'];
        $supplier_alamat = $supplier['alamat'];
        $supplier_kontak = $supplier['kontak'];

        $formData['tanggal'] = date('Y-m-d H:i:s');
        $formData['supplier_nama'] = $supplier_nama;
        $formData['supplier_alamat'] = $supplier_alamat;
        $formData['supplier_kontak'] = $supplier_kontak;

        $this->fakturHdrModel->insert($formData);
        return redirect()->to('transaksi/efaktur')->with('message', 'eFaktur berhasil ditambah.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturEdit($no_faktur)
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Transaksi/eFaktur/Edit";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 1;
      $data['buttonLable'] = 'Edit eFaktur';
      $data['buttonClass'] = 'btn btn-primary';
      $data['processURL'] = 'transaksi/efaktur/edit-process';

      $no_faktur = hex2bin($no_faktur);
      $efaktur = $this->fakturHdrModel->find($no_faktur);
      $data['no_faktur'] = $efaktur['no'];
      $data['supplier_id'] = $efaktur['supplier_id'];
      
      $data['suppliers'] = $this->supplierModel->findAll();
      $data['produks'] = $this->produkModel->findAll();
      $data['fakturdtl'] = $this->fakturDtlModel->where('no', $no_faktur)->findAll();
      return view('layouts/transaksi-efaktur-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturEditProcess()
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'supplier_id' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Supplier tidak boleh kosong.'
          ]
        ],
      ]);

      $no_faktur = $this->request->getPost('no_faktur');
      $supplier_id = $this->request->getPost('supplier_id');

      $formData = [
        'supplier_id' => $supplier_id
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        return redirect()->to('transaksi/efaktur/edit/' . bin2hex($no_faktur));
      } else {
        $supplier = $this->supplierModel->find($supplier_id);
        $supplier_nama = $supplier['nama'];
        $supplier_alamat = $supplier['alamat'];
        $supplier_kontak = $supplier['kontak'];

        $formData['supplier_nama'] = $supplier_nama;
        $formData['supplier_alamat'] = $supplier_alamat;
        $formData['supplier_kontak'] = $supplier_kontak;

        $this->fakturHdrModel->update($no_faktur, $formData);
        return redirect()->to('transaksi/efaktur')->with('message', 'eFaktur berhasil diedit.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturHapus($no_faktur)
  {
    if ($this->session->has('userdata')) {
      $data['titlePage'] = "Transaksi/eFaktur/Hapus";
      $data['segment1'] = $this->uri->getSegment(1);
      $data['segment2'] = $this->uri->getSegment(2);
      $data['message'] = $this->session->getFlashdata('message');
      $data['validation'] = $this->session->getFlashdata('validation');
      $data['user'] = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));

      $data['flagEdit'] = 2;
      $data['buttonLable'] = 'Hapus eFaktur';
      $data['buttonClass'] = 'btn btn-danger';
      $data['processURL'] = 'transaksi/efaktur/hapus-process';

      $no_faktur = hex2bin($no_faktur);
      $efaktur = $this->fakturHdrModel->find($no_faktur);
      $data['no_faktur'] = $efaktur['no'];
      $data['supplier_id'] = $efaktur['supplier_id'];
      
      $data['suppliers'] = $this->supplierModel->findAll();
      $data['produks'] = $this->produkModel->findAll();
      $data['fakturdtl'] = $this->fakturDtlModel->where('no', $no_faktur)->findAll();
      return view('layouts/transaksi-efaktur-form', $data);
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturHapusProcess()
  {
    if ($this->session->has('userdata')) {

      $no_faktur = $this->request->getPost('no_faktur');

      $this->fakturHdrModel->delete($no_faktur);
      $this->fakturDtlModel->where('no', $no_faktur)->delete();
      return redirect()->to('transaksi/efaktur')->with('message', 'eFaktur berhasil dihapus.');
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturPrint($no_faktur, $filename){
    if ($this->session->has('userdata')) {
      $sesi = json_decode($this->encrypter->decrypt((hex2bin($this->session->get('userdata')))));
      $sesi_nama = $sesi->nama;
      $sesi_divisi = $sesi->divisi;

      $no_faktur = hex2bin($no_faktur);

      $fakturhdr = $this->fakturHdrModel->where('no', $no_faktur)->find();
      $no_faktur = $fakturhdr[0]['no'];
      $tanggal = date('d F Y', strtotime($fakturhdr[0]['tanggal']));
      $supplier_nama = $fakturhdr[0]['supplier_nama'];
      $supplier_alamat = $fakturhdr[0]['supplier_alamat'];
      $supplier_kontak = $fakturhdr[0]['supplier_kontak'];

      $fakturdtl = $this->fakturDtlModel->where('no', $no_faktur)->find();

      $html = '';
      $html .= '
        <style>
          @page{
            font-family: Arial, Helvetica, sans-serif;
          }
        </style>

        <table style="width: 100%;">
          <tr>
            <td style="vertical-align: top;">
              <b>PT Bhinneka Sangkuriang Transport</b><br>
              Jl. Pilang Raya
              <br><br><br><br><br>
              No. Faktur: '.$no_faktur.'
            </td>
            <td style="width: 30%;"></td>
            <td style="vertical-align: top;">
              Kepada Yth:<br>
              '.$supplier_nama.'<br>
              '.$supplier_alamat.'<br>
              UP: '.$supplier_kontak.'
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <table border="2" cellspacing="0" cellpadding="5" style="width: 100%;">
                <tr>
                  <th>ID Produk</th>
                  <th>Nama Produk</th>
                  <th>Satuan</th>
                  <th>Jumlah</th>
                  <th>Harga</th>
                  <th>Total Harga</th>
                </tr>
      ';

      $grand_jumlah = 0;
      $grand_harga = 0;
      $grand_total = 0;
      foreach($fakturdtl as $isiFakturDtl){
        $produk_id = $isiFakturDtl['produk_id'];
        $produk_nama = $isiFakturDtl['produk_nama'];
        $produk_satuan = $isiFakturDtl['produk_satuan'];
        $jumlah = $isiFakturDtl['jumlah'];
        $produk_harga = $isiFakturDtl['produk_harga'];
        $total_harga = $jumlah * $produk_harga;

        $grand_jumlah += $jumlah;
        $grand_harga += $produk_harga;
        $grand_total += $total_harga;

        $html .='
                  <tr>
                    <td>'.$produk_id.'</td>
                    <td>'.$produk_nama.'</td>
                    <td>'.$produk_satuan.'</td>
                    <td style="text-align: right;">'.$jumlah.'</td>
                    <td style="text-align: right;">'.number_format($produk_harga).'</td>
                    <td style="text-align: right;">'.number_format($total_harga).'</td>
                  </tr>
        ';
      }
      $html .='
                <tr>
                  <td colspan="3" style="font-weight: bold; text-align: center;">Total</td>
                  <td style="text-align: right;">'.$grand_jumlah.'</td>
                  <td style="text-align: right;">'.number_format($grand_harga).'</td>
                  <td style="text-align: right;">'.number_format($grand_total).'</td>
                </tr>
              </table>
              <br><br><br>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">
              '.$sesi_divisi.'
              <br><br><br><br><br>
              <b>'.$sesi_nama.'</b>
            </td>
            <td></td>
            <td style="text-align: center;">
              Cirebon, '.$tanggal.'
              <br><br><br><br><br>
              <b>'.$supplier_kontak.'</b>
            </td>
          </tr>
        </table>
      ';

      $options = new Options();
      $options->set('defaultFont', 'Courier');
      $options->set('isRemoteEnabled', TRUE);
      $options->set('debugKeepTemp', TRUE);
      $options->set('isHtml5ParserEnabled', true);
      $options->set('chroot', '');
      
      // instantiate and use the dompdf class
      $dompdf = new Dompdf($options);
      // $dompdf->loadHtmlFile(FCPATH.'assets/admin/dist/sample/sample-report.html');
      $dompdf->loadHtml($html);
      
      // (Optional) Setup the paper size and orientation
      $dompdf->setPaper('A4', 'landscape');
      
      // Render the HTML as PDF
      $dompdf->render();
      
      // Output the generated PDF to Browser
      $dompdf->stream($filename, array("Attachment" => false));
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturDtlAddProcess($no_faktur)
  {
    if ($this->session->has('userdata')) {

      $validation = $this->validate([
        'produk_id' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Produk tidak boleh kosong.'
          ]
        ],
        'jumlah' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Kolom Jumlah tidak boleh kosong.'
          ]
        ]
      ]);

      $no_faktur = hex2bin($no_faktur);
      $produk_id = $this->request->getPost('produk_id');
      $jumlah = $this->request->getPost('jumlah');

      $formData = [
        'no' => $no_faktur,
        'produk_id' => $produk_id,
        'jumlah' => $jumlah
      ];

      if (!$validation) {
        $this->session->setFlashdata('validation', $this->validator);
        return redirect()->to('transaksi/efaktur/edit/'.bin2hex($no_faktur));
      } else {
        
        $produk = $this->produkModel->find($produk_id);
        $produk_nama = $produk['nama'];
        $produk_satuan = $produk['satuan'];
        $produk_harga = $produk['harga'];

        $formData['produk_nama'] = $produk_nama;
        $formData['produk_satuan'] = $produk_satuan;
        $formData['produk_harga'] = $produk_harga;

        $this->fakturDtlModel->insert($formData);
        return redirect()->to('transaksi/efaktur/edit/'.bin2hex($no_faktur))->with('message', 'Detail eFaktur berhasil ditambah.');
      }
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }

  public function efakturDtlHapusProcess()
  {
    if ($this->session->has('userdata')) {

      $id = $this->request->getPost('id');
      $no_faktur = $this->request->getPost('no_faktur');

      $this->fakturDtlModel->delete($id);
      return redirect()->to('transaksi/efaktur/edit/'.bin2hex($no_faktur))->with('message', 'Detail eFaktur berhasil dihapus.');
    } else {
      return redirect()->to('login')->with('message', 'Silahkan masuk terlebih dahulu.');
    }
  }
}
