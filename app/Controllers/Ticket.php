<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Ticket extends BaseController
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

        // Lakukan permintaan GET ke API untuk mendapatkan data show saat ini
        $response = $client->get($this->baseApiUrl . '/tickets', [
            'headers' => $this->headers, // Menggunakan header dari constructor
        ]);

        // Decode respons JSON ke array PHP
        $responseData = json_decode($response->getBody(), true);

        // var_dump($responseData);

        // Periksa apakah respons berhasil
        if (isset($responseData['data']) && is_array($responseData['data'])) {
            // Kirim data ke view
            return view('layouts/components/tiket/tiket', ['tickets' => $responseData['data']]);
        } else {
            // Tangani error jika API mengembalikan error
            return view('layouts/components/tiket/tiket', ['error' => 'Failed to retrieve data from API']);
        }
    }

    public function shufflePage()
    {
        $client = service('curlrequest');

        // Lakukan permintaan GET ke API untuk mendapatkan data show saat ini
        $response = $client->get($this->baseApiUrl . '/tickets', [
            'headers' => $this->headers, // Menggunakan header dari constructor
        ]);

        // Decode respons JSON ke array PHP
        $responseData = json_decode($response->getBody(), true);

        // var_dump($responseData);

        // Periksa apakah respons berhasil
        if (isset($responseData['data']) && is_array($responseData['data'])) {
            // Kirim data ke view
            return view('layouts/components/tiket/shuffle', ['tickets' => $responseData['data']]);
        } else {
            // Tangani error jika API mengembalikan error
            return view('layouts/components/tiket/shuffle', ['error' => 'Failed to retrieve data from API']);
        }
    }
}
