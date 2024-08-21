<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Client;

class Auth extends BaseController
{
    private $baseApiUrl;

    public function __construct()
    {
        // Set default base URL for the external API
        $this->baseApiUrl = env('EXTERNAL_API_BASE_URL');
    }

    /**
     * Function to update the base API URL dynamically.
     *
     * @param string $url
     * @return void
     */
    public function setBaseUrl(string $url)
    {
        $this->baseApiUrl = $url;
    }

    /**
     * View function for the controller.
     *
     * @return void
     */
    public function index()
    {


        echo view('layouts/components/auth/login');
    }

    /**
     * Login function using an external API.
     *
     * @return ResponseInterface
     */


    public function login()
    {
        $client = service('curlrequest');

        // Send a POST request to the external API for login
        $response = $client->post($this->baseApiUrl . '/users/login', [
            'form_params' => [
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password')
            ],
            // No headers are sent in this request
        ]);

        $responseData = json_decode($response->getBody(), true);

        if ($response->getStatusCode() === 200) {
            // Store the received token in the session
            $token = $responseData['data']['token']; // Adjust according to the response structure
            session()->set('auth_token', $token);

            // Optionally, store other user data in the session
            //  session()->set('user_data', $responseData['data']);

            // Redirect to the dashboard or another page
            return redirect()->to('/dashboard');
        } else {
            // Handle login error
            return redirect()->back()->with('error', 'Login failed. Please check your credentials.');
        }
    }

    public function logout()
    {
        // Ambil token dari sesi
        $authToken = session()->get('auth_token');

        if ($authToken) {
            // Lakukan permintaan DELETE ke API untuk logout
            $client = service('curlrequest');

            $response = $client->delete($this->baseApiUrl . '/users/current', [
                'headers' => [
                    'X-API-TOKEN' => $authToken,
                ],
            ]);

            // Optional: Periksa respons dari API untuk memastikan logout berhasil
            if ($response->getStatusCode() !== 200) {
                // Jika gagal, bisa tambahkan pesan kesalahan atau log error
                log_message('error', 'Logout API call failed.');
            }
        }

        // Hapus semua data sesi
        session()->destroy();

        // Redirect pengguna ke halaman login
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
