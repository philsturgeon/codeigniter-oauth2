<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Odnoklassniki OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Victor Davydov @septembermd me@victordavydov.org
 * @copyright  (c) 2013 Victor Davydov
 * @license    MIT http://victordavydov.mit-license.org/
 */

class OAuth2_Provider_Odnoklassniki extends OAuth2_Provider
{	
	/**
	 * request method, currently only POST is supported
	 * @var string
	 */
	protected $method = 'POST';

	public function url_authorize()
	{
		return 'http://www.odnoklassniki.ru/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'http://api.odnoklassniki.ru/oauth/token.do';
	}

	public function get_sig(OAuth2_Token_Access $token) 
	{
		return md5('application_key=' . $this->public_key . 'method=users.getCurrentUser' . md5($token->access_token. $this->client_secret));
	}

	public function get_user_info(OAuth2_Token_Access $token)
	{
		$url = 'http://api.odnoklassniki.ru/fb.do?'.http_build_query(array(
			'access_token' => $token->access_token,
			'application_key' => $this->public_key,
			'method' => 'users.getCurrentUser',
			'sig' => $this->get_sig($token),
		));

		$user = json_decode(file_get_contents($url));		
		
		return array(
			'uid' => $user->uid,
			'nickname' => null,
			'name' => isset($user->name) ? $user->name : null,
			'first_name' => isset($user->first_name) ? $user->first_name : null,
			'last_name' => isset($user->last_name) ? $user->last_name : null,
			'birthday' => isset($user->birthday) ? $user->birthday : null,
			'gender' => isset($user->gender) ? $user->gender : null,	
			'location' => isset($user->location) ? $user->location : null,
			'has_email' => isset($user->has_email) ? $user->has_email : null,
			'locale' => isset($user->locale) ? $user->locale : null,
			'online' => isset($user->online) ? $user->online : null,
			'description' => null,
			'image' => isset($user->pic_2) ? $user->pic_2 : null,
			'image1' => isset($user->pic_1) ? $user->pic_1 : null,
			'urls' => array(),
		);
	}
}
