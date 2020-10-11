<?php

declare(strict_types=1);

namespace Lavendar77\Slarb;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class Validator
 * @package Lavendar77\Slarb
 * 
 * @author Adeyinka M. Adefolurin <folurinyinka@gmail.com>
 * @copyright 2020 Adeyinka M. Adefolurin
 * @link https://github.com/Lavendar77/Slarb Simple Laravel API Response Builder
 *
 * @method bool validateHttpCode() Validate the specified HTTP Code.
 */
class Validator {
	/**
	 * Validate the set HTTP Code.
	 *
	 * @param bool $status
	 * @param int $httpCode
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public static function validateHttpCode(bool $status, int $httpCode)
	{
		if (!array_key_exists($httpCode, Response::$statusTexts)) {
			throw new \InvalidArgumentException("The HTTP status code {$httpCode} is not valid.");
		}

		$statusMessage = $status ? 'success' : 'error';

		if ($status === true and $httpCode >= Response::HTTP_BAD_REQUEST) {
			throw new \InvalidArgumentException(
				"The HTTP status code {$httpCode} is not valid for a {$statusMessage} request."
			);
		}

		if ($status === false and ($httpCode >= Response::HTTP_OK and $httpCode < Response::HTTP_BAD_REQUEST)) {
			throw new \InvalidArgumentException(
				"The HTTP status code {$httpCode} is not valid for a {$statusMessage} request."
			);
		}

		return true;
	}
}
