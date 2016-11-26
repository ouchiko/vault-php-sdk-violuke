<?php
namespace Jippi\Vault\Services;

use Jippi\Vault\Client;
use Jippi\Vault\OptionsResolver;

/**
 * This service class handle all Vault HTTP API endpoints starting in /transit/
 *
 */
class Transit
{
    /**
     * Client instance
     *
     * @var Client
     */
    private $client;

    /**
     * Create a new Sys service with an optional Client
     *
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    public function getKey($keyName)
    {
		return $this->client->get('/transit/keys/' . urlencode($keyName));
    }

    public function createKey($keyName, array $body = [])
    {
		$body = OptionsResolver::resolve($body, ['type', 'derived', 'convergent_encryption']);
		$body = OptionsResolver::required($body, ['type']);

		$params = [
			'body' => json_encode($body)
		];

		return $this->client->put('/transit/keys/' . urlencode($keyName), $params);
    }


}
