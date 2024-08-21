<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Show extends BaseController
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
        $response = $client->get($this->baseApiUrl . '/shows/current', [
            'headers' => $this->headers, // Menggunakan header dari constructor
        ]);

        // Decode respons JSON ke array PHP
        $responseData = json_decode($response->getBody(), true);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            // Kirim data ke view
            return view('layouts/components/show/show', ['shows' => $responseData['data']]);
        } else {
            // Tangani error jika API mengembalikan error
            return view('layouts/components/show/show', ['error' => 'Failed to retrieve data from API']);
        }
    }

    public function store()
{
    $client = service('curlrequest');

    // Ambil data dari request POST
    $data = [
        'title' => $this->request->getPost('title'),
        'description' => $this->request->getPost('description'),
        'duration' => $this->request->getPost('duration'),
        'price' => $this->request->getPost('price'),
        'theaterId' => $this->request->getPost('theater_id'),
        'showtimeId' => $this->request->getPost('showtime_id'),
    ];

    // Lakukan permintaan POST ke API untuk menyimpan data baru
    $response = $client->post($this->baseApiUrl . '/shows', [
        'headers' => $this->headers,
        'form_params' => $data,
    ]);

    // Periksa apakah respons berhasil
    if ($response->getStatusCode() === 201) {
        return redirect()->to('/shows')->with('success', 'Show successfully created.');
    } else {
        return redirect()->back()->with('error', 'Failed to create show.');
    }
}

public function update($id)
{
    $client = service('curlrequest');

    // Ambil data dari request POST
    $data = [
        'title' => $this->request->getPost('title'),
        'description' => $this->request->getPost('description'),
        'duration' => $this->request->getPost('duration'),
        'price' => $this->request->getPost('price'),
        'theaterId' => $this->request->getPost('theater_id'),
        'showtimeId' => $this->request->getPost('showtime_id'),
    ];

    // Lakukan permintaan PUT ke API untuk mengedit data yang ada
    $response = $client->patch($this->baseApiUrl . '/shows/' . $id, [
        'headers' => $this->headers,
        'form_params' => $data,
    ]);

    // Periksa apakah respons berhasil
    if ($response->getStatusCode() === 200) {
        return redirect()->to('/shows')->with('success', 'Show successfully updated.');
    } else {
        return redirect()->back()->with('error', 'Failed to update show.');
    }
}

public function delete($id)
{
    $client = service('curlrequest');

    // Lakukan permintaan DELETE ke API untuk menghapus data
    $response = $client->delete($this->baseApiUrl . '/shows/' . $id, [
        'headers' => $this->headers,
    ]);

    // Periksa apakah respons berhasil
    if ($response->getStatusCode() === 200) {
        return redirect()->to('/shows')->with('success', 'Show successfully deleted.');
    } else {
        return redirect()->back()->with('error', 'Failed to delete show.');
    }
}


}
