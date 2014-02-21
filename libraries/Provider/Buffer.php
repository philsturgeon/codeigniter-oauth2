<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth2_Provider_Buffer extends OAuth2_Provider
{
	var $access_token = NULL;
	
	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST';
	
	public $uid_key = 'id';

	public function url_authorize()
	{
		return 'https://bufferapp.com/oauth2/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.bufferapp.com/1/oauth2/token.json';
	}

	public function get_user_info(OAuth2_Token_Access $token=NULL)	{
		
		$token_value = NULL;
		
		if ( !empty($token) ) {
			$token_value = $token->access_token;
		}
		
		$url_get_user_info = 'https://api.bufferapp.com/1/user.json';
		
		if ( empty ($token_value)) {
			throw new Exception('Access token empty or invalid!',array('token' => $token));
		}
		$userinfo = $this->_call('user',array('access_token' => $token_value));
	
		if ( ! empty ($userinfo) ) {
			return $userinfo;
		} else {
			return NULL;
		}
	}
	
	protected function _call($command,$args) {
		$url = "https://api.bufferapp.com/1/$command.json";
		
		if (empty($args['access_token'])) {
			$args['access_token'] = $this->access_token;
		};
		
		$url_call = $url . '?' . http_build_query($args);
		
		
		echo (__METHOD__ . ": Calling url for $command : $url_call ");
		
		$response = file_get_contents($url_call);
		
		if ( ! empty ( $response ) ) {
			return json_decode ($response);
		} else {
			return NULL;
		}
	}
	
}
