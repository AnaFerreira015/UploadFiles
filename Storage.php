<?php
    require './vendor/autoload.php';
    use Google\Auth\Credentials\GCECredentials;
    use Google\Auth\Middleware\AuthTokenMiddleware;
    use Google\Auth\HttpHandler\HttpHandlerFactory;
    use Google\Cloud\Storage\StorageClient;
    use GuzzleHttp\Client;
    use GuzzleHttp\HandlerStack;


    $bucketName = 'website-staging-258919.appspot.com'; 

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
        'projectId' => 'website-staging-258919',
        'httpHandler' => $httpHandler,
        'httpHandler' => function ($request, $options = []) use ($guzzleClient) { 
            return $guzzleClient->send($request, $options);
        }
    ]);
    $storage->registerStreamWrapper();
    
    $bucket = $storage->bucket($bucketName);
    
    $file = fopen('paleta.png', 'r');

    $object = $bucket->upload($file, [
        'name' => 'paleta.png'
    ]);
?>