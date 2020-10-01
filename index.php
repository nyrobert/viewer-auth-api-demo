<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

// shared secret with IBM Video Streaming
// required for creating the signed hash
// you can set up secret on the IBM Video Streaming dashboard or with the API
const SECRET = 'GP7xjVzY';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);

	$response->getBody()->write($twig->render('index.html'));

	return $response;
});

$app->post('/auth', function (Request $request, Response $response, $args) {
	$data = $request->getParsedBody();

	// authentication in your system, can be anything
	if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) && $data['password'] === 'ibm') {
		// array of parameters that will be hashed
		// the value of user field will be used as viewer identifier
		// viewer identifier is used in Viewer tracking product and in the Analytics API
		$userData = [
			'user' => $data['email'],
		];

		// set hash expiration to 5 minutes
		$expiration = time() + 300;

		// hash creation
		$hash = md5(implode('|', $userData) . '|' . $expiration . '|' . SECRET);

		// auth response creation with JSON encoding
		$authResponse = array_chunk(
			array_merge($userData, ['hashExpire' => $expiration, 'hash' => $hash]),
			1,
			true
		);
		$authResponse = json_encode($authResponse);
	} else {
		// just return the string false if the authentication failed
		$authResponse = 'false';
	}

	// pass the auth response back to IBM Video Streaming player
	return $response
		->withHeader('Location', 'https://video.ibm.com/embed/hashlock/pass?hash=' . urlencode($authResponse))
		->withStatus(302);
});

$app->run();
