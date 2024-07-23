<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Search Application</title>
            <link rel="stylesheet" href="css/styles.css">
        </head>
        <body>
            <div class="container">
                <h1>Search Application</h1>
                <form action="/search" method="POST">
                    <input type="text" name="search_term" placeholder="Enter search term" required>
                    <button type="submit">Search</button>
                </form>
            </div>
        </body>
        </html>
    ');
    return $response;
});

$app->post('/search', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $search_term = $data['search_term'] ?? '';

    $validator = new \App\Validator();

    if ($validator->isXSS($search_term)) {
        return $response->withHeader('Location', '/')->withStatus(302);
    } elseif ($validator->isSQLInjection($search_term)) {
        return $response->withHeader('Location', '/')->withStatus(302);
    } else {
        $response->getBody()->write('
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Search Result</title>
                <link rel="stylesheet" href="css/styles.css">
            </head>
            <body>
                <div class="container">
                    <p>Search Term: ' . htmlspecialchars($search_term) . '</p>
                    <br><a href="/">Return to Home</a>
                </div>
            </body>
            </html>
        ');
    }

    return $response;
});

$app->run();
