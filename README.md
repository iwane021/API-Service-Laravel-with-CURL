# API Service with CURL PHP Laravel

Simple API Service to call endpoint of API

## Installation :

- Please put `ApiService.php` to **app/Services**
- Open **config/app.php** file and add service provider in alias.

```sh
'aliases' => [
    ...
    'Api' => App\Services\ApiFacade::class,
],
```

## How to Use :

**GET**

```
\Api::query([
        'url' => env('BASE_URL_MB_API').'SalesOrderHeader'.$params,
        'method' => 'GET'
    ]);
```

**POST**
For laravel you can make code like this :

```
$input = $request->all();
\Api::query([
        'url' => env('BASE_URL_MB_API').'OrderStore',
        'method' => 'POST',
        'body' => $input
    ]);
```

## Another option to use in PHP :

Copy part of script on file `ApiService.php` and put to your class, for example :

```
public function query(array $params) {

	$options = array(
		CURLOPT_URL => $params['url'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		// CURLOPT_MAXREDIRS => 10,
		// CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $params['method'],
	);

	if(isset($params['body'])) {
		$options[CURLOPT_POSTFIELDS] = http_build_query($params['body']);
	}

	if(isset($params['header'])) {
		$options[CURLOPT_HTTPHEADER] = array_merge($this->header, $params['header']);
	} else {
		$options[CURLOPT_HTTPHEADER] = $this->header;
	}

	$curl = curl_init();
	curl_setopt_array($curl, $options);
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	if ($err) {
		return $err;
	} else {
		return json_decode($response);
	}
}
```

Add this code to _Construct_ :

```
private $headers;

public function __construct()
{
	$token = env('MY_API_KEY');

	$this->header = array(
		"AccessToken: " . $token,
		"Authorization: Bearer " . $token,
		// "Authorization: bearer " . session('api_key'),
		// "cache-control: no-cache",
		// "Content-Type:application/x-www-form-urlencoded;charset=UTF-8",
		"Content-Type:application/json;charset=UTF-8",
		"Accept-Charset:UTF-8"
	);
}
```

**Call GET :**

```
$content = $this->query([
		'url' => env('MY_API_HOST').'api/support',
		'method' => 'GET'
	]);

$support = json_decode(json_encode($content), true);
```

**Call POST :**

```
$input = $request->all();

$content = $this->query([
		'url' => env('MY_API_HOST').'api/support/access/store',
		'method' => 'POST',
		'body' => $input
	]);

$support = json_decode(json_encode($content), true);
```

**To get param request for POST Method :**

```
$input = $request->all();
$content = $request->getContent();
parse_str($content, $output);

var_dump($output);
```

## Call API with file_get_contents in PHP :

Copy part of script on file `ContactController.php` and put to your class, for example :

```
public function streamcontent(array $params)
    {
        $this->headers['http']['method'] = $params['method'];
        $this->headers['http']['content'] = isset($params['body']) ? $params['body'] : '';

        $context = stream_context_create($this->headers);
        $url = $params['url'];
        $content = file_get_contents($url, false, $context);

        return $content;
    }
```

Add this code to _Construct_ :

```
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
```

**Call GET :**

```
$content = $this->streamcontent([
		'url' => env('CONTACT_API_HOST').'api/support/access',
		'method' => 'GET'
	]);

$support = json_decode($content, true);
```

**Call POST :**

```
$input = $request->all();
$body = json_encode($input);

$content = $this->streamcontent([
		'url' => env('CONTACT_API_HOST').'api/support/access/store',
		'method' => 'POST',
		'body' => $body
	]);

$support = json_decode($content, true);
```

**To get param request for POST Method :**

```
$output = [
	'token' => $request->header('AccessToken'),
	'input' => $request->all(),
	'email' => $request->input('email')
];

var_dump($output);
```

or view _ContactController.php_ for more info file_get_contents.

Thanks to : [dandisy](https://github.com/dandisy)

If you have trouble or problem send email to : <iwan.webdeveloper@gmail.com>
