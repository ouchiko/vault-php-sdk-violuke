<?php
namespace Violuke\Vault\Services;

use Violuke\Vault\Client;
use Violuke\Vault\OptionsResolver;

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
     * Transit Path (allows different transit points)
     *
     * @var Transit
     */
    private $transitPath = "transit";

    /**
     * Create a new Sys service with an optional Client
     *
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * Sets an alternate transit path, defaults to 'transit'
     *
     * @param <string> transitPath
     */
    public function setTransitPath($transitPath)
    {
        $this->transitPath = $transitPath;
    }

    public function getKey($keyName)
    {
		return $this->client->get('/v1/'.$this->transitPath.'/keys/' . urlencode($keyName));
    }

    public function createKey($keyName, array $body = [])
    {
		$body = OptionsResolver::resolve($body, ['type', 'derived', 'convergent_encryption']);
		$body = OptionsResolver::required($body, ['type']);

		$params = [
			'body' => json_encode($body)
		];

		return $this->client->put('/v1/'.$this->transitPath.'/keys/' . urlencode($keyName), $params);
    }

    public function rotateKey($keyName)
    {
        return $this->client->post('/v1/'.$this->transitPath.'/keys/' . urlencode($keyName) . '/rotate');
    }

    public function encrypt($keyName, $plainText, array $body = [])
    {
        $body = OptionsResolver::resolve($body, ['context', 'nonce']);
        $body['plaintext'] = base64_encode($plainText);

        $params = [
            'body' => json_encode($body)
        ];

        $response = $this->client->post('/v1/'.$this->transitPath.'/encrypt/' . urlencode($keyName), $params);
        return json_decode($response->getBody(), true)['data']['ciphertext'];
    }

    public function decrypt($keyName, $cipherText, array $body = [])
    {
        $body = OptionsResolver::resolve($body, ['context', 'nonce']);
        $body['ciphertext'] = $cipherText;

        $params = [
            'body' => json_encode($body)
        ];

        $response = $this->client->post('/v1/'.$this->transitPath.'/decrypt/' . urlencode($keyName), $params);
        return base64_decode(json_decode($response->getBody(), true)['data']['plaintext']);
    }

    public function rewrap($keyName, $cipherText, array $body = [])
    {
        $body = OptionsResolver::resolve($body, ['context', 'nonce']);
        $body['ciphertext'] = $cipherText;

        $params = [
            'body' => json_encode($body)
        ];

        $response = $this->client->post('/v1/'.$this->transitPath.'/rewrap/' . urlencode($keyName), $params);
        return json_decode($response->getBody(), true)['data']['ciphertext'];
    }
}
