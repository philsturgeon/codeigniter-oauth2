<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth2_Provider_Facebook extends OAuth2_Provider
{  
	public $name = 'facebook';

	public $uid_key = 'uid';
	
	public $scope = 'email,read_stream';

	public function url_authorize()
	{
		return 'https://www.facebook.com/dialog/oauth';
	}

	public function url_access_token()
	{
		return 'https://graph.facebook.com/oauth/access_token';
	}

	public function get_user_info($token)
	{
		$url = 'https://graph.facebook.com/me?'.http_build_query(array(
			'access_token' => $token,
		));

		$user = json_decode(file_get_contents($url));

		// Create a response from the request
		return array(
			'nickname' => $user->username,
			'name' => $user->name,
			'location' => $user->hometown->name,
			'description' => $user->bio,
			'image' => 'http://graph.facebook.com/' . $user->id . '/picture?type=normal',
			'urls' => array(
			  'Facebook' => $user->link,
			),
			'credentials' => array(
				'uid' => $user->id,
				'provider' => $this->name,
				'token' => $token,
			),
		);
	}
}