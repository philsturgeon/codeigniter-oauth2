<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Amazon OAuth2 Provider
 *
 * @package	CodeIgniter/OAuth2
 * @category	Provider
 * @author	Claas Augner
 * @license	http://philsturgeon.co.uk/code/dbad-license
 */

class OAuth2_Provider_Amazon extends OAuth2_Provider
{
	protected $scope = array('profile');
	/**
	 * @var	string	the method to use when requesting tokens
	 */
	protected $method = "POST";

	public function url_authorize()
	{
		return 'https://www.amazon.com/ap/oa';
	}

	public function url_access_token()
	{
		return 'https://api.amazon.com/auth/o2/token';
	}

	public function get_user_info(OAuth2_Token_Access $token)
	{
		$url = 'https://www.amazon.com/ap/user/profile?'.http_build_query(array(
			'access_token' => $token->access_token
		));

		$user = json_decode(file_get_contents($url));

		// Create a response from the request
		return array(
			'uid' => $user->Profile->CustomerId,
			'name' => isset($user->Profile->Name) ? $user->Profile->Name : null,
			'email' => isset($user->Profile->PrimaryEmail) ? $user->Profile->PrimaryEmail : null
		);
	}
}
