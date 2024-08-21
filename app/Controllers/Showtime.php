<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Showtime extends BaseController
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

        // Lakukan permintaan GET ke API untuk mendapatkan data showtime saat ini
        $response = $client->get($this->baseApiUrl . '/showtimes/current', [
            'headers' => $this->headers, // Menggunakan header dari constructor
        ]);

        // Decode respons JSON ke array PHP
        $responseData = json_decode($response->getBody(), true);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            // Kirim data ke view
            return view('layouts/components/show/showtime', ['showtimes' => $responseData['data']]);
        } else {
            // Tangani error jika API mengembalikan error
            return view('layouts/components/show/showtime', ['error' => 'Failed to retrieve data from API']);
        }
    }

    public function store()
    {
        $client = service('curlrequest');

        // Ambil data dari request POST
        $data = [
            'showDate' => $this->request->getPost('showDate'),
            'showTime' => $this->request->getPost('showTime'),
        ];

        // Lakukan permintaan POST ke API untuk menyimpan data baru
        $response = $client->post($this->baseApiUrl . '/showtime', [
            'headers' => $this->headers,
            'form_params' => $data,
        ]);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 201) {
            return redirect()->to('/showtime')->with('success', 'Showtime successfully added.');
        } else {
            return redirect()->back()->with('error', 'Failed to add showtime.');
        }
    }

    public function update($id)
    {
        $client = service('curlrequest');

        // Ambil data dari request POST
        $data = [
            'showDate' => $this->request->getPost('showDate'),
            'showTime' => $this->request->getPost('showTime'),
        ];

        // Lakukan permintaan PATCH ke API untuk mengedit data yang ada
        $response = $client->patch($this->baseApiUrl . '/showtime/' . $id, [
            'headers' => $this->headers,
            'form_params' => $data,
        ]);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            return redirect()->to('/showtime')->with('success', 'Showtime successfully updated.');
        } else {
            return redirect()->back()->with('error', 'Failed to update showtime.');
        }
    }

    public function delete($id)
    {
        $client = service('curlrequest');

        // Lakukan permintaan DELETE ke API untuk menghapus data
        $response = $client->delete($this->baseApiUrl . '/showtime/' . $id, [
            'headers' => $this->headers,
        ]);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            return redirect()->to('/showtime')->with('success', 'Showtime successfully deleted.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete showtime.');
        }
    }
}
