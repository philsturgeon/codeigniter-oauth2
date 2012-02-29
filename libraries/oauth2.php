<?php
	
/**
 * OAuth2.0
 *
 * Authorize 3rd party application via OAuth
 *
 * @author Phil Sturgeon < @philsturgeon > (Original Author)
 * @author Taufan Aditya (Porting to PHP5 style)
 */
class OAuth2 {

	/**
	 * @var bool Initializing status
	 */
	public static $init = FALSE;

	/**
	 * @var object Provider
	 */
	public $provider;

	/**
	 * Constructor
	 *
	 * @param  string  Provider name
	 * @throws OAuth2_Exception
	 * @return object  Corresponding provider
	 */
	function __construct($provider = '', $options = NULL)
	{
		if ( ! static::$init)
		{
			// Register OAuth2 autoloader
			spl_autoload_register(array($this, '_autoloader'));

			static::$init = TRUE;
		}

		// Build the provider
		if ( ! empty($provider))
		{
			$class = 'OAuth2\\Provider\\'.ucfirst($provider);
			$this->provider = new $class($options);
		}
	}
	
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
		$instance = new static($name, $options);

		return $instance->provider;
	}

	protected function _autoloader($name)
	{
		// Only triggered by specific OAuth2 fragment
		if (strpos($name, 'OAuth2') !== FALSE)
		{
			$dir = dirname(__FILE__).DIRECTORY_SEPARATOR;
			$filename = strtolower(str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $name)).'.php';

			dump($dir.$filename);
			include($dir.$filename);
		}
	}
}