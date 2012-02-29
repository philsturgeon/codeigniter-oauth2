<?php namespace OAuth2\Provider;

/**
 * OAuth Provider Facebook
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  Phil Sturgeon
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

use \OAuth2\Provider;

class Facebook extends Provider {
	/**
	 * @var string Provider name
	 */
	public $name = 'facebook';

	/**
	 * @var string UID key
	 */
	public $uid_key = 'uid';
	
	/**
	 * @var array Request scope
	 */
	public $scope = array('email', 'read_stream');
	
	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_authorize()
	{
		return 'https://www.facebook.com/dialog/oauth';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_access_token()
	{
		return 'https://graph.facebook.com/oauth/access_token';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function get_user_info($token, $as_object = FALSE)
	{
		// Get the access token
		$access_token = ($token instanceof \Oauth2\Token\Access) ? $token->access_token : $token;

		$url = 'https://graph.facebook.com/me?'.http_build_query(array(
			'access_token' => $access_token,
		));

		$user = json_decode(file_get_contents($url));

		if ($as_object)
		{
			// We're done
			return $user;
		}
		else
		{
			// Create a response from the request
			return array(
				'uid' => $user->id,
				'nickname' => $user->username,
				'name' => $user->name,
				'email' => isset($user->email) ? $user->email : null,
				'location' => isset($user->hometown->name) ? $user->hometown->name : null,
				'description' => isset($user->bio) ? $user->bio : null,
				'image' => 'https://graph.facebook.com/me/picture?type=normal&access_token='.$access_token,
				'urls' => array(
				  'Facebook' => $user->link,
				),
			);
		}
	}
}
