<?php
	
include('Exception.php');
include('Token.php');
include('Provider.php');

/**
 * OAuth2.0
 *
 * @author Phil Sturgeon < @philsturgeon >
 */
class OAuth2 {
	
	/**
	 * Create a new provider.
	 *
	 *     // Load the Twitter provider
	 *     $provider = $this->oauth2->provider('twitter');
	 *
	 * @param   string   provider name
	 * @param   array    provider options
	 * @return  OAuth_Provider
	 */
	public static function provider($name, array $options = NULL)
	{
		include_once 'Provider/'.ucfirst($name).'.php';
		
		$class = 'OAuth2_Provider_'.ucfirst($name);

		return new $class($options);
	}
	
}