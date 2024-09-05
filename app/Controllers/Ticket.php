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

    public function shufflePageIndex()
    {
        $client = service('curlrequest');

        // Lakukan permintaan GET ke API untuk mendapatkan data show saat ini
        $response = $client->get($this->baseApiUrl . '/tickets/shuffle-tickets', [
            'headers' => $this->headers, // Menggunakan header dari constructor
        ]);

        // Decode respons JSON ke array PHP
        $responseData = json_decode($response->getBody(), true);

        // var_dump($responseData);

        // Periksa apakah respons berhasil
        if (isset($responseData['data']) && is_array($responseData['data'])) {
            // Kirim data ke view
            return view('layouts/components/tiket/tmpshuffle', ['shuffle' => $responseData['data']]);
        } else {
            // Tangani error jika API mengembalikan error
            return view('layouts/components/tiket/tmpshuffle', ['error' => 'Failed to retrieve data from API']);
        }
    }

    public function shuffle($id = null)
{
    $client = service('curlrequest');

    // Menangkap data POST dari form
    $post = $this->request->getPost();

    // Misalnya, Anda ingin mengirimkan shuffleCount sebagai bagian dari URL
    $shuffleCount = isset($post['shuffleCount']) ? $post['shuffleCount'] : null;

    if ($shuffleCount === null) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Shuffle count is required.']);
    }

    // Membuat URL dengan parameter shuffleCount
    $url = $this->baseApiUrl . '/tickets/shuffle-tickets/' . $shuffleCount;

    // Lakukan permintaan POST ke API dengan URL yang sudah diatur
    $response = $client->post($url, [
        'headers' => $this->headers, // Menggunakan header dari constructor
    ]);

    // Decode respons JSON ke array PHP
    $responseData = json_decode($response->getBody(), true);

    // Periksa apakah respons berhasil
    if (isset($responseData['data']) && is_array($responseData['data'])) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Tickets shuffled successfully', 'data' => $responseData['data']]);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to retrieve data from API']);
    }
}

}
