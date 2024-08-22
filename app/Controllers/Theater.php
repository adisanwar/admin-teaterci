<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Error;

class Theater extends BaseController
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

        // Lakukan permintaan GET ke API untuk mendapatkan data teater saat ini
        $response = $client->get($this->baseApiUrl . '/theaters/current', [
            'headers' => $this->headers,
        ]);

        // Decode respons JSON ke array PHP
        $responseData = json_decode($response->getBody(), true);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            // Kirim data ke view
            return view('layouts/components/show/teater', ['theaters' => $responseData['data']]);
        } else {
            // Tangani error jika API mengembalikan error
            return view('layouts/components/show/teater', ['error' => 'Failed to retrieve data from API']);
        }
    }

    // public function store()
    // {
    //     $client = service('curlrequest');

    //     // Ambil data dari request POST
    //     $name = $this->request->getPost('name');
    //     $location = $this->request->getPost('location');
    //     $capacity = $this->request->getPost('capacity');

    //     // Siapkan data untuk dikirim ke API
    //     $data = [
    //         'name'     => $name,
    //         'location' => $location,
    //         'capacity' => $capacity,
    //     ];

    //     try {
    //         // Kirim data ke API
    //         $response = $client->post($this->baseApiUrl . '/theaters', [
    //             'headers' => $this->headers,
    //             'form_params' => $data,
    //         ]);

    //         // Periksa apakah respons berhasil
    //         if ($response->getStatusCode() === 201) {
    //             return redirect()->to('/theaters')->with('success', 'Theater successfully added.');
    //         } else {
    //             // Dapatkan pesan error dari API jika ada
    //             $error = $response->getBody();
    //             return redirect()->back()->with('error', 'Failed to add theater. ' . $error);
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    //     }
    // }

    public function store()
{
    $client = service('curlrequest');

    // Retrieve data from the POST request
    $name = $this->request->getPost('name');
    $location = $this->request->getPost('location');
    $capacity = $this->request->getPost('capacity');
    $photo = $this->request->getFile('photo');

    // Create the data array for form data
    $data = [
        [
            'name'     => 'name',
            'contents' => $name
        ],
        [
            'name'     => 'location',
            'contents' => $location
        ],
        [
            'name'     => 'capacity',
            'contents' => $capacity
        ]
    ];

    // Handle photo upload if a file was uploaded
    if ($photo && $photo->isValid() && !$photo->hasMoved()) {
        $data[] = [
            'name'     => 'photo',
            'contents' => fopen($photo->getTempName(), 'r'),
            'filename' => $photo->getClientName() // Add the original filename
        ];
    }

    var_dump($data);

    try {
        // Send the request to the API
        $response = $client->post($this->baseApiUrl . '/theaters', [
            'headers'  => $this->headers,
            'multipart' => $data // Use multipart for file uploads
        ]);

        // Check if the request was successful
        if ($response->getStatusCode() === 201) {
            return redirect()->to('/theaters')->with('success', 'Theater successfully added.');
        } else {
            // Get error message from the API if any
            $error = $response->getBody();
            return redirect()->back()->with('error', 'Failed to add theater. ' . $error);
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}


    public function update($id)
    {
        $client = service('curlrequest');

        // Ambil data dari request POST
        $data = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location'),
            'capacity' => $this->request->getPost('capacity'),
        ];

        // Handle file upload jika ada
        if ($this->request->getFile('photo')->isValid()) {
            $photo = $this->request->getFile('photo');
            $photoName = $photo->getRandomName();
            $photo->move(WRITEPATH . 'uploads', $photoName);
            $data['photo'] = $photoName;
        }

        // Lakukan permintaan PATCH ke API untuk mengedit data yang ada
        $response = $client->patch($this->baseApiUrl . '/theaters/' . $id, [
            'headers' => $this->headers,
            'form_params' => $data,
        ]);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            return redirect()->to('/theaters')->with('success', 'Theater successfully updated.');
        } else {
            return redirect()->back()->with('error', 'Failed to update theater.');
        }
    }


    public function delete($id)
    {
        $client = service('curlrequest');

        // Lakukan permintaan DELETE ke API untuk menghapus data
        $response = $client->delete($this->baseApiUrl . '/theaters/' . $id, [
            'headers' => $this->headers,
        ]);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            return redirect()->to('/theaters')->with('success', 'Theater successfully deleted.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete theater.');
        }
    }
}
