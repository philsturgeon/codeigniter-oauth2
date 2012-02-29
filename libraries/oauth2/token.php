<?php namespace OAuth2;

/**
 * OAuth2 Token
 *
 * @package    OAuth2
 * @category   Token
 * @author     Phil Sturgeon
 * @copyright  (c) 2011 HappyNinjas Ltd
 */

abstract class Token {

	/**
	 * Create a new token object.
	 *
	 *     $token = OAuth2_Token::factory($name);
	 *
	 * @param   string  token type
	 * @param   array   token options
	 * @return  Token
	 */
	public static function factory($type = 'access', array $options = null)
	{
		$class = '\\OAuth2\\Token\\'.ucfirst($type);
		
		return new $class($options);
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the token secret
	 *     $secret = $token->secret;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}
	
	/**
	 * Return a boolean if the property is set
	 *
	 *     // Get the token secret
	 *     if ($token->secret) exit('YAY SECRET');
	 *
	 * @param   string  variable name
	 * @return  bool
	 */
	public function __isset($key)
	{
		return isset($this->$key);
	}

} // End Token
