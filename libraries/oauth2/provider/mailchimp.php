<?php namespace OAuth2\Provider;

/**
 * OAuth Provider Mailchimp
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  Phil Sturgeon
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

use \OAuth2\Provider;

class Mailchimp extends Provider {
	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST';

	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_authorize()
	{
		return 'https://login.mailchimp.com/oauth2/authorize';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function url_access_token()
	{
		return 'https://login.mailchimp.com/oauth2/token';
	}

	/**
	 * @see ./oauth2/provider.php
	 */
	public function get_user_info(\OAuth2\Token\Access $token, $as_object = FALSE)
	{
		if ($as_object)
		{
			// We're done
			return $user;
		}
		else
		{
			// Create a response from the request
			return array(
				'uid' => $token->access_token,
			);
		}
	}
}
