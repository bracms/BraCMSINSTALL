<?php

namespace Bra\install\objects;

use Bra\core\Holder;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class BraCurl
 * @mixin Client
 */
class BraCurl extends Holder {
	public static Client $holder;

	public function __construct ($config = [], $req_type = 'ajax') {
		$config['headers'] = is_array($config['headers']) ? $config['headers'] : [];
		if ($req_type == 'ajax') {
			$config['headers'] = array_merge(
				[
					'Accept' => 'application/json, text/javascript, */*; q=0.01',
					'x-requested-with' => 'XMLHttpRequest'
				],
				$config['headers']
			);
		}
		self::$holder = new Client($config);
	}

	/**
	 * @param $url
	 * @param string $method
	 * @param array $data
	 * @return StreamInterface  | MessageInterface
	 * @throws GuzzleException
	 */
	public function fetch ($url, $method = 'GET', $data = []) {
		$response = self::$holder->request($method, $url, $data);

		return $response;
	}

	public function fetch_ansyc ($url, $method = 'GET', $data = []) {
		$promise = self::$holder->requestAsync($method, $url);
		$promise->then(
			function (ResponseInterface $res) {
				echo $res->getStatusCode() . "\n";
			},
			function (RequestException $e) {
				echo $e->getMessage() . "\n";
				echo $e->getRequest()->getMethod();
			}
		);
	}

	/**
	 * @param $url
	 * @param string $method
	 * @param array $data
	 * @return StreamInterface | MessageInterface
	 * @throws GuzzleException
	 */
	public function test_url ($url, $method = 'GET', $data = []) {
		$response = $this->fetch($url, $method, $data);

		return $response;
	}

	public function get_content ($url, $method = 'GET', $data = [], $format = true) {
		$response = $this->fetch($url, $method, $data);
		$content = $response->getBody();
		if ($format) {
			$content = json_decode($content, 1);
		}

		return $content;
	}

	public function get_api ($url, $method = 'GET', $form_data = [], $format = true) {
		$params = ['form_params' => $form_data];
		if ($this->debug) {
			$params['debug'] = $this->debug;
		}
		$response = self::$holder->request($method, $url, $params);
		$content = $response->getBody()->__toString();
		if ($format === true) {
			$content = json_decode($content, 1);
		}
		if ($format === "jsonp") {
		}

		return $content;
	}

	public function get_stream_api ($url, $method, $params, $save_path, $format = true) {
		$resource = fopen($save_path, 'w');
		$options = [
			RequestOptions::JSON => $params,
			'sink' => $resource
		];
		$response = self::$holder->request($method, $url, $options);

		return bra_res((int) ($response->getBody() instanceof Stream));
	}
}
