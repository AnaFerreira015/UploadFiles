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
        'handler' => $stack,
        'auth' => getenv('GOOGLE_APPLICATION_CREDENTIALS')
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

    // foreach ($storage->buckets() as $bucket) {
    //     echo(' BUCKET: '.$bucket->name());
    // }
    $file = fopen('specs.png', 'r');
    // echo('FILE SIZE: '.filesize('specs.png'));
    $bucket = $storage->bucket($bucketName);
    // file_put_contents(`gs://${$bucketName}/Uploads/specs.png`, $file);
    $object = $bucket->upload($file, [
        'name' => 'Uploads/specs.png'
    ]);
?>