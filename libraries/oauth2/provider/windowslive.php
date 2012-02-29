<?php namespace OAuth2\Provider;

/**
 * OAuth Provider Windows Live
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  Phil Sturgeon
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

use \OAuth2\Provider;

class Windowslive extends Provider
{
	/**
	 * @see ./oauth2/provider.php
	 */
	public $name = 'windowslive';

	/**
	 * @see ./oauth2/provider.php
	 */
	public $uid_key = 'uid';
	
	/**
	 * @see ./oauth2/provider.php
	 */
	public $scope = array('wl.basic', 'wl.emails');
	
	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST';
	
	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_authorize()
	{
		return 'https://oauth.live.com/authorize';
	}
	
	/**
	 * @see ./oauth2/provider.php
	 */
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
	public function get_user_info(\OAuth2\Token\Access $token, $as_object = FALSE)
	{
		// define the get user information token
		$url = 'https://apis.live.net/v5.0/me?'.http_build_query(array(
			'access_token' => $token->access_token,
		));
		
		// perform network request
		$user = json_decode(file_get_contents($url));

		if ($as_object)
		{
			// We're done
			return $user;
		}
		else
		{
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
}
