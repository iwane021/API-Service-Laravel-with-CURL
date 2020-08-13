<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller {

	private $headers;

    public function __construct()
    {
        $token = env('CONTACT_API_KEY');

        $this->headers = [
            "http" => [
                "method" => "GET",
                "header" => "Content-Type: application/json\r\n".
                        "AccessToken: {$token}\r\n".
                        "Authorization: Bearer {$token}",
                "content" => ""
            ]
        ];
    }

    public function show()
    {
        $content = $this->streamcontent([
                'url' => env('CONTACT_API_HOST').'api/support/access',
                'method' => 'GET'
            ]);

        $support = json_decode($content, true);
		
        return view('contact.support', ['support' => $support]);
    }

    public function form($id)
    {
        $content = $this->streamcontent([
                'url' => env('CONTACT_API_HOST').'api/support/access/'.$id,
                'method' => 'GET'
            ]);

        $support = json_decode($content, true);
        // dd($support);
        return view('contact.index', ['support' => $support]);
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $body = json_encode($input);

        $content = $this->streamcontent([
                'url' => env('CONTACT_API_HOST').'api/support/access/store',
                'method' => 'POST',
                'body' => $body
            ]);
        
        $support = json_decode($content, true);
		
        return view('contact.index', ['data' => $support]);
    }

    public function streamcontent(array $params)
    {
        $this->headers['http']['method'] = $params['method'];
        $this->headers['http']['content'] = isset($params['body']) ? $params['body'] : '';

        $context = stream_context_create($this->headers);
        $url = $params['url'];
        $content = file_get_contents($url, false, $context);

        return $content;
    }

}
