<?php namespace OAuth2\Provider;

/**
 * OAuth Provider Google
 *
 * @package    CodeIgniter/OAuth
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  Phil Sturgeon
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

class Youtube extends Google {  
	
	/**
	 * @see ./oauth2/provider/google.php
	 */
	public $name = 'youtube';
	
	/**
	 * @see ./oauth2/provider/google.php
	 */
	public $human = 'YouTube';

	/**
	 * @see ./oauth2/provider/google.php
	 */
	public function __construct(array $options = array())
	{
		// Now make sure we have the default scope to get user data
		empty($options['scope']) and $options['scope'] = array('https://www.google.com/m8/feeds', 'http://gdata.youtube.com');
	
		// Array it if its string
		$options['scope'] = (array) $options['scope'];

		parent::__construct($options);
	}
}
