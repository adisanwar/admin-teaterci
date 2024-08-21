<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Client;

class Home extends BaseController
{
    private $headers;
    private $baseApiUrl;

    public function __construct()
    {
        // Inisialisasi base URL dan header
        $this->baseApiUrl = env('EXTERNAL_API_BASE_URL');

        // Ambil token dari sesi dan set ke header jika ada
        $authToken = session()->get('auth_token');
        $this->headers = [];

        if ($authToken) {
            $this->headers = [
                'X-API-TOKEN' => $authToken,
            ];
        }
    }


    public function dashboard()
    {
        // Ambil token dari sesi
        session()->setFlashdata('message', 'Silahkan Login Dulu');
        $authToken = session()->get('auth_token');

        // Periksa apakah token ada
        if (!$authToken) {
            // Jika token tidak ada, alihkan ke halaman login
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        // Tampilkan halaman dashboard
        return view('dashboard');
    }

    
}
