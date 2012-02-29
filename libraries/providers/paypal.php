<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class OAuth2_Provider_Paypal extends OAuth2_Provider
{

    public $name = 'paypal';
    public $uid_key = 'uid';

    /**
     * @var  string  the method to use when requesting tokens
     */
    protected $method = 'POST';
    public $scope = array('https://identity.x.com/xidentity/resources/profile/me');

    public function url_authorize()
    {
        return 'https://identity.x.com/xidentity/resources/authorize';
    }

    public function url_access_token()
    {
        return 'https://identity.x.com/xidentity/oauthtokenservice';
    }

    public function get_user_info($token)
    {
        $url = 'https://identity.x.com/xidentity/resources/profile/me?' . http_build_query(array(
                    'oauth_token' => $token
                ));

        $user = json_decode(file_get_contents($url));
		$user = $user->identity;
		return array(
		            'uid' => $user['userId'],
		            'nickname' => url_title($user['fullName'], '_', true),
		            'name' => $user['fullName'],
		            'first_name' => $user['firstName'],
		            'last_name' => $user['lastName'],
		            'email' => $user['emails'][0],
		            'location' => $user->addresses[0],
		            'image' => null,
		            'description' => null,
		            'urls' => array(
						'PayPal' => null
					)
		        );
    }

}
