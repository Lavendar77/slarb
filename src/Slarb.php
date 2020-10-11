<?php

declare(strict_types=1);

namespace Lavendar77\Slarb;

use Lavendar77\Slarb\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
	public const SLARB_KEY_DATA = 'data';
	public const SLARB_KEY_MESSAGE = 'message';
	public const SLARB_KEY_STATUS = 'status';

	public const SLARB_ERROR_MESSAGE = 'Request failed';
	public const SLARB_PROCESSING_MESSAGE = 'Request is processing';
	public const SLARB_SUCCESS_MESSAGE = 'Request was successful';

	private bool $_status;
	private int $_httpCode;
	private string $_message;
	private $_data;

	/**
	 * @param bool $status
	 * @param int $httpCode
	 * @param string $message
	 * @param mixed $data
	 */
	protected function __construct(bool $status, int $httpCode, string $message, $data = null)
	{
		$this->_status = $status;
		$this->_httpCode = $httpCode;
		$this->_message = $message;
		$this->_data = $data;
	}

	/**
	 * Set a fresh response.
	 * 
	 * @return self
	 */
	public static function respond(bool $status): object
	{
		return new self(
			$status,
			Response::HTTP_CONTINUE,
			static::SLARB_PROCESSING_MESSAGE
		);
	}

	/**
	 * Set a success response.
	 * 
	 * @return self
	 */
	public static function success(): object
	{
		return new self(
			true,
			Response::HTTP_OK,
			static::SLARB_SUCCESS_MESSAGE
		);
	}

	/**
	 * Set an error response.
	 * 
	 * @return self
	 */
	public static function error(): object
	{
		return new self(
			false,
			Response::HTTP_BAD_REQUEST,
			static::SLARB_ERROR_MESSAGE
		);
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
		Validator::validateHttpCode($this->_status, $httpCode);

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
