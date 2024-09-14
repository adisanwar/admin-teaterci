<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use GuzzleHttp\Client;

class Show extends BaseController
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

    $shows = json_decode($showResponse->getBody(), true)['data'] ?? [];
    $theaters = json_decode($theaterResponse->getBody(), true)['data'] ?? [];
    $showtimes = json_decode($showtimeResponse->getBody(), true)['data'] ?? [];

    return view('layouts/components/show/show', [
        'shows' => $shows,
        'theaters' => $theaters,
        'showtimes' => $showtimes,
        'baseImgUrl' => $this->baseImgUrl,
    ]);
}


public function store()
{
    $client = new Client();

    // Retrieve data from the POST request
    $post = $this->request->getPost();
    $photo = $this->request->getFile('photo');

    // if (
    //     !$this->validate([
    //         'title' => 'required',
    //         'photo' => 'uploaded[image]|is_image[image]|max_size[image,1024]',
    //         'price' => 'required|numeric',
    //         'duration' => 'required',
    //         'rating' => 'required',
    //         'description' => 'required',
    //         'theaterId' => 'required|numeric',
    //         'showtimeId' => 'required|numeric',
    //     ])
    // ) {
    //     return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    // }

    $data = [
        [
            'name'     => 'title',
            'contents' => $post['title']
        ],
        [
            'name' => 'photo',
            'contents' => fopen($photo->getRealPath(), 'r'),
            'filename' => $photo->getClientName(),
            'headers' => ['Content-Type' => $photo->getClientMimeType()],
        ],
        [
            'name'     => 'price',
            'contents' => $post['price']
        ],
        [
            'name'     => 'duration',
            'contents' => $post['duration']
        ],
        [
            'name'     => 'rating',
            'contents' => $post['rating']
        ],
        [
            'name'     => 'description',
            'contents' => $post['description']
        ],
        [
            'name'     => 'theaterId',
            'contents' => $post['theaterId']
        ],
        
        [
            'name'     => 'showtimeId',
            'contents' => $post['showtimeId']
        ]
    ];
    
    // $data = [
    //     'title' => $this->request->getPost('title'),
    //     'description' => $this->request->getPost('description'),
    //     'duration' => $this->request->getPost('duration'),
    //     'price' => $this->request->getPost('price'),
    //     'theaterId' => $this->request->getPost('theater_id'),  // Convert to integer
    // 'showtimeId' => $this->request->getPost('showtime_id'), 
    // ];

    // var_dump($data);
    // Send the request to the API
    try {
    $response = $client->post($this->baseApiUrl . '/shows/', [
        'headers' => $this->headers,
        'multipart' => $data,
    ]);

    $body = $response->getBody();
    $responseArray = json_decode($body, true);

    if (isset($responseArray['error'])) {
        return redirect()->back()->withInput()->with('errors', $responseArray['error']);
    }

    return redirect()->to('/show')->with('success', 'Show successfully added.');
    } catch (\Exception $e) {
        log_message('error', 'API request error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to add Show. ' . $e->getMessage());
        //throw $th;
    }
    
}


public function update($id)
{
    $client = new Client();

    $post = $this->request->getPost();
    $photo = $this->request->getFile('photo');

    $data = [];
    
    // Check and add only non-empty fields to the data array
    if (!empty($post['title'])) {
        $data[] = [
            'name'     => 'title',
            'contents' => $post['title']
        ];
    }

    if (!empty($post['description'])) {
        $data[] = [
            'name'     => 'description',
            'contents' => $post['description']
        ];
    }
    if (!empty($post['rating'])) {
        $data[] = [
            'name'     => 'rating',
            'contents' => $post['rating']
        ];
    }
    if (!empty($post['price'])) {
        $data[] = [
            'name'     => 'price',
            'contents' => $post['price']
        ];
    }

    if (!empty($post['duration'])) {
        $data[] = [
            'name'     => 'duration',
            'contents' => $post['duration']
        ];
    }
    if (!empty($post['theaterId'])) {
        $data[] = [
            'name'     => 'theaterId',
            'contents' => $post['theaterId']
        ];
    }
    if (!empty($post['showtimeId'])) {
        $data[] = [
            'name'     => 'showtimeId',
            'contents' => $post['showtimeId']
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

    var_dump($data);

    // Ambil data dari request POST
    
    try {
        // Lakukan permintaan PUT ke API untuk mengedit data yang ada
    $response = $client->patch($this->baseApiUrl . '/shows/' . $id, [
        'headers' => $this->headers,
        'multipart' => $data,
    ]);

    $body = $response->getBody();
    $responseArray = json_decode($body, true);

    // var_dump($responseArray['error']);
    if (isset($responseArray['error'])) {
        return redirect()->back()->withInput()->with('errors', $responseArray['error']);
    }

    return redirect()->to('/show')->with('success', 'Theater successfully added.');
    } catch (\Exception $e) {
        log_message('error', 'API request error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to add theater. ' . $e->getMessage());
    //     //throw $th;
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
        return redirect()->to('/show')->with('success', 'Show successfully deleted.');
    } else {
        return redirect()->back()->with('error', 'Failed to delete show.');
    }
}


}
