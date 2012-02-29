<?php namespace OAuth2\Provider;

/**
 * OAuth Provider Soundcloud
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  Phil Sturgeon
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

use \OAuth2\Provider;

class Soundcloud extends Provider {
	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST';

	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_authorize()
	{
		return 'https://soundcloud.com/connect';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_access_token()
	{
		return 'https://api.soundcloud.com/oauth2/token';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function get_user_info(\OAuth2\Token\Access $token, $as_object = FALSE)
	{
		$url = 'https://api.soundcloud.com/me.json?'.http_build_query(array(
			'oauth_token' => $token->access_token,
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
				'name' => $user->full_name,
				'location' => $user->country.' ,'.$user->country,
				'description' => $user->description,
				'image' => $user->avatar_url,
				'urls' => array(
					'MySpace' => $user->myspace_name,
					'Website' => $user->website,
				),
			);
		}
	}
}
