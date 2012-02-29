<?php namespace OAuth2\Provider;

/**
 * OAuth Provider Github
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  Phil Sturgeon
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

use \OAuth2\Provider;

class Github extends Provider {
	/**
	 * @var string Provider name
	 */
	public $name = 'github';
	
	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_authorize()
	{
		return 'https://github.com/login/oauth/authorize';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_access_token()
	{
		return 'https://github.com/login/oauth/access_token';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function get_user_info(\OAuth2\Token\Access $token)
	{
		$url = 'https://api.github.com/user?'.http_build_query(array(
			'access_token' => $token->access_token,
		));

		$user = json_decode(file_get_contents($url));

		// Create a response from the request
		return array(
			'uid' => $user->id,
			'nickname' => $user->login,
			'name' => $user->name,
			'email' => $user->email,
			'urls' => array(
			  'GitHub' => 'http://github.com/'.$user->login,
			  'Blog' => $user->blog,
			),
		);
	}
}