<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use GuzzleHttp\Client;

class Theater extends BaseController
{

    private $headers;
    private $baseApiUrl;

    public $baseImgUrl;

    public function __construct()
    {
        // Inisialisasi base URL dan header
        $this->baseApiUrl = env('EXTERNAL_API_BASE_URL');

        $this->baseImgUrl = env('EXTERNAL_IMG_BASE_URL');


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
        $client = new Client();

        // Lakukan permintaan GET ke API untuk mendapatkan data teater saat ini
        $response = $client->get($this->baseApiUrl . '/theaters/current', [
            'headers' => $this->headers,
        ]);

        // Decode respons JSON ke array PHP
        $responseData = json_decode($response->getBody(), true);
        
        // var_dump($responseData);

        // Periksa apakah respons berhasil
        if ($response->getStatusCode() === 200) {
            // Kirim data ke view
            return view('layouts/components/show/teater', [
                'theaters' => $responseData['data'],
                'baseImgUrl' => $this->baseImgUrl,
            ]);
        } else {
            // Tangani error jika API mengembalikan error
            return view('layouts/components/show/teater', ['error' => 'Failed to retrieve data from API']);
        }
    }


    public function store()
    {
        $client = new Client(); // Ensure you are using the correct namespace
    
        // Retrieve data from the POST request
        $post = $this->request->getPost();
        $photo = $this->request->getFile('photo');

        // if (
        //     !$this->validate([
        //         'name' => 'required',
        //         'photo' => 'uploaded[image]|is_image[image]|max_size[image,1024]',
        //         'price' => 'required|numeric',
        //         'location' => 'required',
        //         'capacity' => 'required',
        //     ])
        // ) {
        //     return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        // }
    
        // Create the data array for form data
        $data = [
            [
                'name'     => 'name',
                'contents' => $post['name']
            ],
            [
                'name'     => 'location',
                'contents' => $post['location']
            ],
            [
                'name' => 'photo',
                'contents' => fopen($photo->getRealPath(), 'r'),
                'filename' => $photo->getClientName(),
                'headers' => ['Content-Type' => $photo->getClientMimeType()],
            ],
            [
                'name'     => 'capacity',
                'contents' => $post['capacity']
            ]
        ];

        // $data = [];
    
        // // Check and add only non-empty fields to the data array
        // if (!empty($post['name'])) {
        //     $data[] = [
        //         'name'     => 'name',
        //         'contents' => $post['name']
        //     ];
        // }
    
        // if (!empty($post['location'])) {
        //     $data[] = [
        //         'name'     => 'location',
        //         'contents' => $post['location']
        //     ];
        // }
    
        // if (!empty($post['capacity'])) {
        //     $data[] = [
        //         'name'     => 'capacity',
        //         'contents' => $post['capacity']
        //     ];
        // }
    
        // if ($photo && $photo->isValid() && !$photo->hasMoved()) {
        //     $data[] = [
        //         'name' => 'photo',
        //         'contents' => fopen($photo->getRealPath(), 'r'),
        //         'filename' => $photo->getClientName(),
        //         'headers' => ['Content-Type' => $photo->getClientMimeType()],
        //     ];
        // }

        // var_dump($data);
    
        try {
            // Send the request to the API
            $response = $client->post($this->baseApiUrl . '/theaters', [
                'headers'  => $this->headers,
                'multipart' => $data
            ]);
    
            $body = $response->getBody();
            $responseArray = json_decode($body, true);
    
            if (isset($responseArray['error'])) {
                return redirect()->back()->withInput()->with('errors', $responseArray['error']);
            }
    
            return redirect()->to('/theaters')->with('success', 'Theater successfully added.');
        } catch (\Exception $e) {
            log_message('error', 'API request error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add theater. ' . $e->getMessage());
        }
    }
    


    public function update($id)
    {
        $client = new \GuzzleHttp\Client();
    
        // Retrieve data from the POST request
        $post = $this->request->getPost();
        $photo = $this->request->getFile('photo');
    
        // Initialize an empty data array for form data
        $data = [];
    
        // Check and add only non-empty fields to the data array
        if (!empty($post['name'])) {
            $data[] = [
                'name'     => 'name',
                'contents' => $post['name']
            ];
        }
    
        if (!empty($post['location'])) {
            $data[] = [
                'name'     => 'location',
                'contents' => $post['location']
            ];
        }
    
        if (!empty($post['capacity'])) {
            $data[] = [
                'name'     => 'capacity',
                'contents' => $post['capacity']
            ];
        }
    
        
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            log_message('debug', 'File is valid and ready for upload: ' . $photo->getClientName());
            $data[] = [
                'name' => 'photo',
                'contents' => fopen($photo->getRealPath(), 'r'),
                'filename' => $photo->getClientName(),
                'headers' => ['Content-Type' => $photo->getClientMimeType()],
            ];
        } else {
            log_message('debug', 'No valid file uploaded or file has already been moved.');
        }

        // var_dump($data);
        // var_dump($photo);

       
        // Proceed only if there is data to update
        if (empty($data)) {
            return redirect()->back()->with('error', 'No data provided to update.');
        }
    
        try {
            // Send the request to the API using PATCH method
            $response = $client->patch($this->baseApiUrl . '/theaters/' . $id, [
                'headers' => $this->headers,
                'multipart' => $data,  // Use multipart for file uploads and form data
            ]);
    
            // Check if the request was successful
            if ($response->getStatusCode() !== 200) {
                return redirect()->back()->with('error', 'Failed to update theater.');
            } else {
                return redirect()->to('/theaters')->with('success', 'Theater successfully updated.');
            }
        } catch (\Exception $e) {
            log_message('error', 'API request error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update theater. ' . $e->getMessage());
        }
    }
    
    


    public function delete($id)
    {
        $client = new Client();

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
