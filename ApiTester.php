<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

$method = 'GET';
$requestUrl = 'http://YOUR_BASE_URL/rest/V1/products/?searchCriteria[pageSize]=20';

$oauthGenerator = new \App\OAuthGenerator($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $requestUrl,
    CURLOPT_HTTPHEADER => [
        $oauthGenerator->getAuthorizationHeader($requestUrl, $method)
    ]
]);

$result = curl_exec($curl);
curl_close($curl);

header('Content-Type: application/json');
echo $result;
