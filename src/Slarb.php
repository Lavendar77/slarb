<?php

declare(strict_types=1);

namespace Lavendar77\Slarb;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Slarb
 * @package Lavendar77\Slarb
 * 
 * @author Adeyinka M. Adefolurin <folurinyinka@gmail.com>
 * @copyright 2020 Adeyinka M. Adefolurin
 * @link https://github.com/Lavendar77/Slarb Simple Laravel API Response Builder
 * 
 * @property bool $_status The status in the response body.
 * @property int $_httpCode The HTTP status code in the response header.
 * @property string $_message The message in the response body.
 * @property mixed $_data The data in the response body.
 *
 * @method self success() Set a success response.
 * @method self error() Set an error response.
 * @method object withMessage(string $text) Set the message.
 * @method object withHttpCode(int $httpCode) Set the HTTP code.
 * @method object withData($data) Set the response data.
 * @method JsonResponse build() Build the response.
 */
class Slarb {
	public const SLARB_KEY_STATUS = 'status';
	public const SLARB_KEY_MESSAGE = 'message';
	public const SLARB_KEY_DATA = 'data';

	private bool $_status;
	private int $_httpCode;
	private string $_message;
	private $_data;

	/**
	 * @param bool $status
	 * @param int $httpCode
	 * @param string $message
	 */
	protected function __construct(bool $status, int $httpCode, string $message)
	{
		$this->_status = $status;
		$this->_httpCode = $httpCode;
		$this->_message = $message;
	}

	/**
	 * Set a success response.
	 * 
	 * @return self
	 */
	public static function success(): object
	{
		return new self(true, Response::HTTP_OK, 'Request Successful');
	}

	/**
	 * Set an error response.
	 * 
	 * @return self
	 */
	public static function error(): object
	{
		return new self(false, Response::HTTP_BAD_REQUEST, 'Request Failed');
	}

	/**
	 * Customize the response message.
	 * 
	 * @param string $text
	 * @return $this
	 */
	public function withMessage(string $text): object
	{
		$this->_message = $text;

		return $this;
	}

	/**
	 * Customize the data in the response.
	 * 
	 * @param mixed $data
	 * @return $this
	 */
	public function withData($data): object
	{
		$this->_data = $data;

		return $this;
	}

	/**
	 * Customize the HTTP Status Code.
	 * 
	 * @param int $httpCode
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function withHttpCode(int $httpCode)
	{
		if (!array_key_exists($httpCode, Response::$statusTexts)) {
			throw new \InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $httpCode));
		}

		$statusMessage = $this->_status ? 'success' : 'error';

		if ($this->_status === true and $httpCode >= Response::HTTP_BAD_REQUEST) {
			throw new \InvalidArgumentException(
				sprintf('The HTTP status code "%s" is not valid for a "%s" request.', $httpCode, $statusMessage)
			);
		}

		if ($this->_status === false and ($httpCode >= Response::HTTP_OK and $httpCode < Response::HTTP_BAD_REQUEST)) {
			throw new \InvalidArgumentException(
				sprintf('The HTTP status code "%s" is not valid for a "%s" request.', $statusCode, $statusMessage)
			);
		}

		$this->_httpCode = $httpCode;

		return $this;
	}

	/**
	 * Build the response in JSON format.
	 *  
	 * @return JsonResponse
	 */
	public function build()
	{
		$response = [
			static::SLARB_KEY_STATUS => $this->_status,
			static::SLARB_KEY_MESSAGE => $this->_message,
			static::SLARB_KEY_DATA => $this->_data,
		];

		return new JsonResponse($response, $this->_httpCode);
	}
}
