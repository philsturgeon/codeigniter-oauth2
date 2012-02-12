<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth2_Provider_Soundcloud extends OAuth2_Provider
{
	public $name = 'soundcloud';
	
	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST';
	
	public $scope = array('non-expiring');

	public function url_authorize()
	{
		return 'https://soundcloud.com/connect';
	}

	public function url_access_token()
	{
		return 'https://api.soundcloud.com/oauth2/token';
	}

	public function get_user_info(OAuth2_Token_Access $token)
	{
		$url = 'https://api.soundcloud.com/me.json?'.http_build_query(array(
			'oauth_token' => $token->access_token,
		));

		$user = json_decode(file_get_contents($url));

		// Create a response from the request
		return array(
			'uid' => $user->id,
			'nickname' => $user->username,
			'name' => $user->full_name,
			// 'email' => isset($user->email) ? $user->email : null,
			'location' => $user->country,
			'description' => $user->description,
			'image' => $user->avatar_url,
			'urls' => array(
				'MySpace' => $user->myspace_name,
				'Website' => $user->website,
			),
		);
	}
}
