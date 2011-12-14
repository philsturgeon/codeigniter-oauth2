<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth2_Provider_Facebook extends OAuth2_Provider
{  
	public $name = 'facebook';

	public $uid_key = 'uid';
	
	public $scope = array('email', 'read_stream');

	public function url_authorize()
	{
		return 'https://www.facebook.com/dialog/oauth';
	}

	public function url_access_token()
	{
		return 'https://graph.facebook.com/oauth/access_token';
	}

	public function get_user_info(OAuth2_Token_Access $token)
	{
		$url = 'https://graph.facebook.com/me?'.http_build_query(array(
			'access_token' => $token->access_token,
		));

		$user = json_decode(file_get_contents($url));

		// Create a response from the request
		return array(
			'uid' => $user->id,
			'nickname' => $user->username,
			'name' => $user->name,
			'email' => $user->email,
			'location' => $user->hometown->name,
			'description' => $user->bio,
			'image' => 'https://graph.facebook.com/me/picture?type=normal&access_token='.$token->access_token,
			'urls' => array(
			  'Facebook' => $user->link,
			),
		);
	}
}
