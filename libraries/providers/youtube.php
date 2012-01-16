<?php
include(__dir__.'/google.php');
class OAuth2_Provider_Youtube extends OAuth2_Provider_Google {  
	
	public $name = 'youtube';
	
	public $human = 'YouTube';

	public function __construct(array $options = array())
	{
		// Now make sure we have the default scope to get user data
		empty($options['scope']) and $options['scope'] = array('https://www.google.com/m8/feeds', 'http://gdata.youtube.com');
	
		// Array it if its string
		$options['scope'] = (array) $options['scope'];

		parent::__construct($options);
	}
}
