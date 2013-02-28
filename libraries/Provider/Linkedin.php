<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * LinkedIn OAuth2 Provider
 * https://developer.linkedin.com/documents/authentication
 * 
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Benjamin Hill benjaminhill@gmail.com
 * @copyright  (c) Same as parent project
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */
class OAuth2_Provider_Linkedin extends OAuth2_Provider {

  public $method = 'POST';
  public $scope_seperator = ' ';

  public function __construct(array $options = array()) {
    if (empty($options['scope'])) {
      $options['scope'] = array(
          'r_basicprofile',
          'r_emailaddress'
      );
    }

    // Array it if its string
    $options['scope'] = (array) $options['scope'];

    parent::__construct($options);
  }

  public function url_authorize() {
    return 'https://www.linkedin.com/uas/oauth2/authorization';
  }

  public function url_access_token() {
    return 'https://www.linkedin.com/uas/oauth2/accessToken';
  }

  public function get_user_info(OAuth2_Token_Access $token) {

    $url_profile = 'https://api.linkedin.com/v1/people/~?format=json&' . http_build_query(array(
                'oauth2_access_token' => $token->access_token,
    ));
    $user = json_decode(file_get_contents($url_profile), true);

    $url_email = 'https://api.linkedin.com/v1/people/~/email-address?format=json&' . http_build_query(array(
                'oauth2_access_token' => $token->access_token,
    ));
    $user_email = json_decode(file_get_contents($url_email), true);

    return array(
        'first_name' => $user['firstName'],
        'last_name' => $user['lastName'],
        'title' => $user['headline'],
        'email' => $user_email,
        'urls' => array(
            'LinkedIn' => $user->siteStandardProfileRequest->url
        ),
    );
  }

}
