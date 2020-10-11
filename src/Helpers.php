<?php

use Lavendar77\Slarb\Slarb;
use Symfony\Component\HttpFoundation\JsonResponse;

if (!function_exists('slarb')) {
	/**
	 * Call the Slarb class directly for a quick response.
	 * 
	 * @param string $status
	 * @param int $httpCode
	 * @param string $message
	 * @param mixed $data
	 * @return Slarb
	 */
	function slarb(string $status, int $httpCode, string $message, $data = null): JsonResponse
	{
		return Slarb::respond($status)
					->withHttpCode($httpCode)
					->withMessage($message)
					->withData($data)
					->build();
	}
}
