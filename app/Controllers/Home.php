<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// use CodeIgniter\HTTP\Client;
use GuzzleHttp\Client;

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
        $client = new Client();
    
        // Fetch shows
        $showResponse = $client->get($this->baseApiUrl . '/shows/current', [
            'headers' => $this->headers,
        ]);
    
        // Fetch theaters
        $theaterResponse = $client->get($this->baseApiUrl . '/theaters/current', [
            'headers' => $this->headers,
        ]);
    
        // Fetch showtimes
        $showtimeResponse = $client->get($this->baseApiUrl . '/showtimes/current', [
            'headers' => $this->headers,
        ]);
    
        // Fetch tickets
        $ticketResponse = $client->get($this->baseApiUrl . '/tickets', [
            'headers' => $this->headers,
        ]);
    
        // Fetch orders
        $orderResponse = $client->get($this->baseApiUrl . '/orders', [
            'headers' => $this->headers,
        ]);
    
        // Fetch users
        $usersResponse = $client->get($this->baseApiUrl . '/users', [
            'headers' => $this->headers,
        ]);
    
        // Decode the JSON response
        $shows = json_decode($showResponse->getBody(), true)['data'] ?? [];
        $theaters = json_decode($theaterResponse->getBody(), true)['data'] ?? [];
        $showtimes = json_decode($showtimeResponse->getBody(), true)['data'] ?? [];
        $tickets = json_decode($ticketResponse->getBody(), true)['data'] ?? [];
        $orders = json_decode($orderResponse->getBody(), true)['data'] ?? [];
        $users = json_decode($usersResponse->getBody(), true)['data'] ?? [];
    
        // Calculate totals
        $totalShows = count($shows);
        $totalTheaters = count($theaters);
        $totalShowtimes = count($showtimes);
        $totalTickets = count($tickets);
        $totalOrders = count($orders);
        $totalUsers = count($users);
    
        // Ambil token dari sesi
        session()->setFlashdata('message', 'Silahkan Login Dulu');
        $authToken = session()->get('auth_token');
    
        // Periksa apakah token ada
        if (!$authToken) {
            // Jika token tidak ada, alihkan ke halaman login
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        // Tampilkan halaman dashboard dengan data total
        return view('dashboard', [
            'totalShows' => $totalShows,
            'totalTheaters' => $totalTheaters,
            'totalShowtimes' => $totalShowtimes,
            'totalTickets' => $totalTickets,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
        ]);
    }

    public function profile()
    {
        $client = new Client();
    
        // Fetch current user
        $usersResponse = $client->get($this->baseApiUrl . '/users/current', [
            'headers' => $this->headers,
        ]);
    
        $user = json_decode($usersResponse->getBody(), true)['data'] ?? [];
    
        // Ambil token dari sesi
        session()->setFlashdata('message', 'Silahkan Login Dulu');
        $authToken = session()->get('auth_token');
    
        // Periksa apakah token ada
        if (!$authToken) {
            // Jika token tidak ada, alihkan ke halaman login
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        // Tampilkan halaman topbar dengan data user
        return view('layouts/components/topbar', ['user' => $user]);
    }
    

}    