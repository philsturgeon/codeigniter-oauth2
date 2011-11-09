<?php
/**
 * OAuth Provider
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  Phil Sturgeon
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

abstract class OAuth2_Provider {

	/**
	 * @var  string  provider name
	 */
	public $name;

	/**
	 * @var  string  uid key name
	 */
	public $uid_key = 'uid';

	/**
	 * @var  array  additional request parameters to be used for remote requests
	 */
	protected $params = array();

	/**
	 * Overloads default class properties from the options.
	 *
	 * Any of the provider options can be set here, such as app_id or secret.
	 *
	 * @param   array   provider options
	 * @return  void
	 */
	public function __construct(array $options = NULL)
	{
		if ( ! $this->name)
		{
			// Attempt to guess the name from the class name
			$this->name = strtolower(substr(get_class($this), strlen('OAuth2_Provider_')));
		}
		
		foreach ($options as $key => $val)
		{
			$this->{$key} = $val;
		}
		
		// Set a default, which will be used if none are provided pre-request
		$this->redirect_uri = get_instance()->config->site_url(get_instance()->uri->uri_string());
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the provider signature
	 *     $signature = $provider->signature;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

	/**
	 * Returns the authorization URL for the provider.
	 *
	 *     $url = $provider->url_authorize();
	 *
	 * @return  string
	 */
	abstract public function url_authorize();

	/**
	 * Returns the access token endpoint for the provider.
	 *
	 *     $url = $provider->url_access_token();
	 *
	 * @return  string
	 */
	abstract public function url_access_token();

	/*
	* Get an authorization code from Facebook.  Redirects to Facebook, which this redirects back to the app using the redirect address you've set.
	*/	
	public function authorize($options = array())
	{
		$state = md5(uniqid(rand(), TRUE));
		get_instance()->session->set_userdata('state', $state);
			
		$params = array(
			'client_id' => $this->client_id,
			'redirect_uri' => isset($options['redirect_uri']) ? $options['redirect_uri'] : $this->redirect_uri,
			'state' => $state,
			'scope' => $this->scope,
			'response_type' => 'code', # required for Windows Live
		);
		
		$url = $this->url_authorize().'?'.http_build_query($params);
		
		redirect($url);
	}

	/*
	* Get access to the API
	*
	* @param	string	The access code
	* @return	object	Success or failure along with the response details
	*/	
	public function access($code)
	{
		$params = array(
			'client_id' => $this->client_id,
			'redirect_uri' => isset($options['redirect_uri']) ? $options['redirect_uri'] : $this->redirect_uri,
			'client_secret' => $this->client_secret,
			'code' => $code,	
			'grant_type' => 'authorization_code', # required for Windows Live
		);
		
		$url = $this->url_access_token().'?'.http_build_query($params);
		
		$response = file_get_contents($url);

		$params = null;

		// TODO: This could be moved to a provider method to reduce provider specific awareness
		switch($this->name)
		{
			case 'windowslive':
				$params = json_decode($response, true);
			break;

			default:
				parse_str($response, $params);
		}

		if (isset($params['error']))
		{
			throw new OAuth2_Exception($params);
		}
		
		return $params;
	}

}
