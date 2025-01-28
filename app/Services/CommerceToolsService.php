<?php

namespace App\Services;

use Commercetools\Api\Client\ApiRequestBuilder;
use Commercetools\Api\Client\ClientCredentialsConfig;
use Commercetools\Api\Client\Config;
use Commercetools\Client\ClientCredentials;
use Commercetools\Client\ClientFactory;
use GuzzleHttp\ClientInterface;

class CommerceToolsService
{
    private string $clientID;
    private string $clientSecret;
    private string $scope;
    private string $region;
    private string $projectKey;

    public function __construct($clientID, $clientSecret, $scope, $region, $projectKey)
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->scope = $scope;
        $this->region = $region;
        $this->projectKey = $projectKey;
    }

    public function createApiClient()
    {
        $authConfig = new ClientCredentialsConfig(
            new ClientCredentials($this->clientID, $this->clientSecret, $this->scope),
            [],
            "https://auth.{$this->region}.commercetools.com/oauth/token"
        );

        $client = ClientFactory::of()->createGuzzleClient(
            new Config([], "https://api.{$this->region}.commercetools.com"),
            $authConfig,
        );

        /** @var ClientInterface $client */
        $builder = new ApiRequestBuilder($client);

        return $builder->withProjectKey($this->projectKey);
    }
}
