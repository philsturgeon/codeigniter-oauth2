# Codeigniter OAuth 2.0

Authorize users with your application in a driver-base fashion meaning one implementation works for multiple oAuth 2 providers. This is only to authenticate onto oauth providers and not to build an oauth service.

Note that this Spark ONLY provides the authorization mechanism. There's an example controller below, however in a later version there will be a full controller.

## Currently Supported

- Facebook
- GitHub
- Windows Live Connect (Windows Live)

## TODO

This is a developing library and currently only supports a small number of oauth2 providers - more refactoring of code is to follow with a full implementation of an authentication class to store users details.

Requests should be done through a more stable system, there however isn't a request class in CodeIgniter.

This does not and will never support any OAuth 1 providers. For that use [codeigniter-oauth] (https://github.com/calvinfroedge/codeigniter-oauth).

## Usage Example

http://example.com/auth/session/facebook

```php
public function session($provider)
{
	$this->load->helper('url_helper');
	$this->load->library('oauth2');
	
	$provider = $this->oauth2->provider($provider, array(
		'client_id' => 'your-client-id',
		'client_secret' => 'your-client-secret',
	));

	if ( ! isset($_GET['code']))
	{
		// By sending no options it'll come back here
		$provider->authorize();
	}
	else
	{
		// Howzit?
		try
		{
			$params = $provider->access($_GET['code']);
			
			$user = $provider->get_user_info($params['access_token']);
			
			// Here you should use this information to A) look for a user B) help a new user sign up with existing data.
			// If you store it all in a cookie and redirect to a registration page this is crazy-simple.
			echo "<pre>";
			var_dump($user);
		}
		
		catch (OAuth2_Exception $e)
		{
			show_error('That didnt work: '.$e);
		}
		
	}
}
```
