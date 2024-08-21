<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Order extends BaseController
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


    public function index()
    {
        $client = service('curlrequest');

        $authToken = session()->get('auth_token');

        // Lakukan permintaan GET ke API untuk mendapatkan data show saat ini
        $response = $client->get($this->baseApiUrl . '/order/current', [
            'headers' => $this->headers, // Menggunakan header dari constructor
        ]);

        // Decode respons JSON ke array PHP
        $responseData = json_decode($response->getBody(), true);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            // Kirim data ke view
            return view('layouts/components/order/order', ['shows' => $responseData['data']]);
        } else {
            // Tangani error jika API mengembalikan error
            return view('layouts/components/order/order', ['error' => 'Failed to retrieve data from API']);
        }
    }
}
