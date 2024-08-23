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

        // Make a GET request to the API to retrieve the current user's data
        try {
            $response = $client->get($this->baseApiUrl . '/users/current', [
                'headers' => $this->headers,
            ]);

            $responseData = json_decode($response->getBody(), true);

            if ($response->getStatusCode() == 200) {
                // Pass the user data to the view
                return view('layouts/components/user/user', ['users' => $responseData['data']]);
            } else {
                // Handle the error and pass the error message to the view
                return view('layouts/components/user/user', ['error' => 'Failed to retrieve data from API']);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the request
            log_message('error', 'API request error: ' . $e->getMessage());
            return view('layouts/components/user/user', ['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }


    public function store()
    {
        $client = service('curlrequest');

        // Ambil data dari request POST
        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'isAdmin' => $this->request->getPost('isAdmin') ? true : false,
        ];

        // Lakukan permintaan POST ke API untuk menyimpan data baru
        $response = $client->post($this->baseApiUrl . '/users', [
            'headers' => $this->headers,
            'form_params' => $data,
        ]);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 201) {
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
