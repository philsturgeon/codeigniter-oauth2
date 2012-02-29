<?php namespace OAuth2\Provider;

/**
 * OAuth Provider Google
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  Phil Sturgeon
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

use \OAuth2\Provider;

class Google extends Provider {  
	/**
	 * @var string Provider name
	 */
	public $name = 'google';
	
	/**
	 * @var string Provider human-readable name
	 */
	public $human = 'Google';

	/**
	 * @var string UID key
	 */
	public $uid_key = 'uid';
	
	/**
	 * @var string default method
	 */
	public $method = 'POST';

	/**
	 * @var string scope separator
	 */
	public $scope_seperator = ' ';

	/**
	 * Constructor
	 */
	public function __construct(array $options = array())
	{
		// Now make sure we have the default scope to get user data
		empty($options['scope']) and $options['scope'] = array(
			'https://www.googleapis.com/auth/userinfo.profile', 
			'https://www.googleapis.com/auth/userinfo.email'
		);
	
		// Array it if its string
		$options['scope'] = (array) $options['scope'];
		
		parent::__construct($options);
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_authorize()
	{
		return 'https://accounts.google.com/o/oauth2/auth';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_access_token()
	{
		return 'https://accounts.google.com/o/oauth2/token';
	}

	/*
	* Get access to the API
	*
	* @param	string	The access code
	* @return	object	Success or failure along with the response details
	*/	
	public function access($code, $options = array())
	{
		if ($code === null)
		{
			throw new \OAuth2\Exception(array('message' => 'Expected Authorization Code from '.ucfirst($this->name).' is missing'));
		}

		return parent::access($code, $options);
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function get_user_info(\OAuth2\Token\Access $token, $as_object = FALSE)
	{
		$url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&'.http_build_query(array(
			'access_token' => $token->access_token,
		));
		
		$user = json_decode(file_get_contents($url), true);

		if ($as_object)
		{
			// We're done
			return $user;
		}
		else
		{
			return array(
				'uid' => $user['id'],
				'nickname' => url_title($user['name'], '_', true),
				'name' => $user['name'],
				'first_name' => $user['given_name'],
				'last_name' => $user['family_name'],
				'email' => $user['email'],
				'location' => null,
				'image' => $user['picture'],
				'description' => null,
				'urls' => array(),
			);
		}
	}
}
