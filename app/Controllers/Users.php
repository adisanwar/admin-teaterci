<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Users extends BaseController
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

        $response = $client->get($this->baseApiUrl . '/users', [
            'headers' => $this->headers,
        ]);

        $responseData = json_decode($response->getBody(), true);

        // var_dump($responseData);

            if (isset($responseData['data']) && is_array($responseData['data'])) {
                // var_dump($responseData); 
                return view('layouts/components/user/user', ['users' => $responseData['data']]);
            } else {
                return view('layouts/components/user/user', ['error' => 'Unexpected response format from API.']);
            }
    }

    public function get_current_user()
    {
        $client = service('curlrequest');

        $response = $client->get($this->baseApiUrl . '/users/current', [
            'headers' => $this->headers,
        ]);

        $responseData = json_decode($response->getBody(), true);

        // Asumsi bahwa respons JSON memiliki kunci 'username'
    if (isset($responseData['username'])) {
        return $responseData['username'];
    } else {
        return 'Guest'; // Atau nilai default lainnya jika tidak ada username
    }
    }


    public function store()
{
    $client = service('curlrequest');

    $post = $this->request->getPost();

    $data = [
        'name'     => $post['name'],
        'username' => $post['username'],
        'password' => $post['password'],
        'isAdmin'  => filter_var($post['isAdmin'], FILTER_VALIDATE_BOOLEAN),
    ];


    // Lakukan permintaan POST ke API untuk menyimpan data baru
    $response = $client->post($this->baseApiUrl . '/users', [
        'headers' => array_merge($this->headers, ['Content-Type' => 'application/json']),
        'json' => $data, // Gunakan 'json' untuk mengirim data dalam format JSON
    ]);

    // Periksa apakah respons berhasil
    if ($response->getStatusCode() === 200) {
        return redirect()->to('/users')->with('success', 'User successfully added.');
    } else {
        return redirect()->back()->with('error', 'Failed to add user.');
    }
}


    public function update($username)
    {
        $client = service('curlrequest');

        // Ambil data dari request POST
        $data = [
            'name' => $this->request->getPost('name'),
            'isAdmin' => $this->request->getPost('isAdmin') ? true : false,
        ];

        // Lakukan permintaan PATCH ke API untuk mengedit data yang ada
        $response = $client->patch($this->baseApiUrl . '/users/' . $username, [
            'headers' => $this->headers,
            'form_params' => $data,
        ]);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            return redirect()->to('/users')->with('success', 'User successfully updated.');
        } else {
            return redirect()->back()->with('error', 'Failed to update user.');
        }
    }

    public function delete($username)
    {
        $client = service('curlrequest');

        // Lakukan permintaan DELETE ke API untuk menghapus data
        $response = $client->delete($this->baseApiUrl . '/users/' . $username, [
            'headers' => $this->headers,
        ]);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            return redirect()->to('/users')->with('success', 'User successfully deleted.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete user.');
        }
    }
}