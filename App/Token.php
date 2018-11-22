<?php
declare(strict_types=1);
namespace App;


class Token
{
	
	/**
	 * The token value
	 * @var string
	 */
	protected $token;
	
	/**
	 * Create a new random token.
	 * @throws \Exception
	 */
	public function __construct($token_value = null)
	{
		if ($token_value) {
			$this->token = $token_value;
		} else {
			$this->token = bin2hex(random_bytes(16)); // 16 bytes = 128 bits = 32 hex character
		}
	}
	
	/**
	 * Get the token value
	 *
	 * @return string The value
	 */
	public function getValue(): string
	{
		return $this->token;
	}
	
	/**
	 * Get the hashed token value
	 *
	 * @return string The hashed value
	 */
	public function getHash(): string
	{
		return hash_hmac('sha256', $this->token, \App\Config::SECRET_KEY); // sha256 = 64 chars
	}
}