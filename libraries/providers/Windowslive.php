<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// OAuth2 Provider for Windows Live Connect
class OAuth2_Provider_Windowslive extends OAuth2_Provider
{
	// variables
	public $name = 'windowslive';
	public $uid_key = 'uid';
	
	public $scope = array('wl.basic', 'wl.emails');
	
	// authorise url
	public function url_authorize()
	{
		return 'https://oauth.live.com/authorize';
	}
	
	// access token url
	public function url_access_token()
	{
		return 'https://oauth.live.com/token';
	}
	
	// get basic user information
	/********************************
	** this can be extended through the 
	** use of scopes, check out the document at
	** http://msdn.microsoft.com/en-gb/library/hh243648.aspx#user
	*********************************/
	public function get_user_info(OAuth2_Token_Access $token)
	{
		// define the get user information token
		$url = 'https://apis.live.net/v5.0/me?'.http_build_query(array(
			'access_token' => $token->access_token,
		));
		
		// perform network request
		$user = json_decode(file_get_contents($url));

		// create a response from the request and return it
		return array(
			'uid' 		=> $user->id,
			'name' 		=> $user->name,
			'nickname' 	=> url_title($user->name, '_', true),
//			'location' 	=> $user[''], # scope wl.postal_addresses is required
										  # but won't be implemented by default
			'locale' 	=> $user->locale,
			'urls' 		=> array('Windows Live' => $user->link),
		);
	}
}
