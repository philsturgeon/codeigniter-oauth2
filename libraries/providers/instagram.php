<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Instagram Provider
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Gustavo Rod. Baldera <https://github.com/gbaldera>
 * @reference     http://instagram.com/developer
 */

class OAuth2_Provider_Instagram extends OAuth2_Provider {
	
	public $name = 'instagram';

	public function url_authorize()
	{
		return 'https://api.instagram.com/oauth/authorize/';
	}

	public function url_access_token()
	{
		return 'https://api.instagram.com/oauth/access_token';
	}

	public function get_user_info(OAuth2_Token_Access $token)
	{
		$url = 'https://api.instagram.com/v1/users/self/?'.http_build_query(array(
			'access_token' => $token->access_token,
		));

		$user = json_decode(file_get_contents($url));
		$user = $user->data;

		// Create a response from the request
		return array(
			'uid' => $user->id,
			'nickname' => $user->username,
			'name' => $user->full_name,
			'email' => null,
			'profile_picture' => $user->profile_picture,
			'bio' => $user->bio,
            'counts' => array(
            	'media' => $user->counts->media,
            	'follows' => $user->counts->follows,
            	'followed_by' => $user->counts->followed_by
            ),
			'urls' => array(
			  'website' => $user->website,
			),
		);
	}
}