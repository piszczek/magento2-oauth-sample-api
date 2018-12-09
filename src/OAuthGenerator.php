<?php

namespace App;

class OAuthGenerator {

    /**
     * @var string
     */
    private $consumerKey;
    /**
     * @var string
     */
    private $consumerSecret;
    /**
     * @var string
     */
    private $accessToken;
    /**
     * @var string
     */
    private $accessTokenSecret;
    /**
     * @var @var \OAuth\OAuth1\Signature\Signature $signature
     */
    private $signature;

    public function __construct(
        string $consumerKey,
        string $consumerSecret,
        string $accessToken,
        string $accessTokenSecret
    ) {

        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->accessToken = $accessToken;
        $this->accessTokenSecret = $accessTokenSecret;

        $credentials = new \OAuth\Common\Consumer\Credentials(
            $consumerKey,
            $consumerSecret,
            ''
        );

        /** @var \OAuth\OAuth1\Signature\Signature $signature */
        $this->signature = new Signature($credentials);
        $this->signature->setHashingAlgorithm('HMAC-SHA1');
        $this->signature->setTokenSecret($accessTokenSecret);
    }


    /**
     * Generate Auth header to inject in CURL request
     *
     * @param string $requestUrl
     * @param string $method
     * @return string
     */
    public function getAuthorizationHeader(string $requestUrl, string $method = 'GET'): string {
        $data = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_nonce' => md5(uniqid(rand(), true)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->accessToken,
            'oauth_version' => '1.0',
        ];

        $uri = new \OAuth\Common\Http\Uri\Uri($requestUrl);

        $sign = $this->signature->getSignature($uri, $data, $method);

        $data['oauth_signature'] = $sign;

        return 'Authorization: OAuth ' . http_build_query($data, '', ',');
    }
}
