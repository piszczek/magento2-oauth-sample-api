# Magento2 API example using OAuth 


#### First run:
 `composer install`

Follow instruction from: https://inviqa.com/blog/magento-2-tutorial-overview-web-api (Setting up an Integration) and 
 set required parameters in `config.php`.
 
 
### Run app
in `ApiTester.php` set `requestUrl` and run

`php ApiTester.php`

or set up webserver and run ApiTester.php file. It should return list of products.





```
   $method = 'GET';
   $requestUrl = '; //resource url 
   
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
```
