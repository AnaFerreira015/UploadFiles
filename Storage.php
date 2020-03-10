<?php
    require './vendor/autoload.php';
    use Google\Auth\Credentials\GCECredentials;
    use Google\Auth\Middleware\AuthTokenMiddleware;
    use Google\Auth\HttpHandler\HttpHandlerFactory;
    use Google\Cloud\Storage\StorageClient;
    use GuzzleHttp\Client;
    use GuzzleHttp\HandlerStack;


    $bucketName = 'your-bucketName-here'; 

    $gce = new GCECredentials();
    $middleware = new AuthTokenMiddleware($gce);

    $stack = HandlerStack::create();
    $stack->push($middleware);

    $client = new Client([
        'handler' => $stack
    ]);

    $httpHandler = HttpHandlerFactory::build($client);
    $guzzleClient = new GuzzleHttp\Client(['verify' => false]);

    $storage = new StorageClient([
        'projectId' => 'your-projectId-here',
        'httpHandler' => $httpHandler,
        'httpHandler' => function ($request, $options = []) use ($guzzleClient) { 
            return $guzzleClient->send($request, $options);
        }
    ]);
    $storage->registerStreamWrapper();
    
    $bucket = $storage->bucket($bucketName);
    
    $file = fopen('specs.png', 'r');

    $object = $bucket->upload($file, [
        'name' => 'Uploads/specs.png'
    ]);

    $qualidade = 100;

    $size = getimagesize('specs.png');
    $tipo = $size[2];
    echo $tipo;

    if($tipo == 2){ // 2 é o JPG
		$img = imagecreatefromjpeg($nome_img);	   
    } if($tipo == 1){ // 1 é o GIF
		$img = imagecreatefromgif($nome_img);	   
    } if($tipo == 3){ // 3 é PNG
		$img = imagecreatefrompng($nome_img);	   
    }
?>