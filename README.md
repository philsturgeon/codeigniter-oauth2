# Codeigniter OAuth 2.0

Authorize users with your application in a driver-base fashion meaning one implementation works for multiple oAuth 2 providers.

Note that this Spark ONLY provides the authorization mechanism. You will need to implement the example controller so you can save this information to make API requests on the users behalf.

## Currently Supported

- Facebook
- GitHub

## TODO

This is just a prototype and so far only works with Facebook, meaning beyond implementing more providers it might just not work with others, so refactoring is going to be important. 

Also, requests need to be WAY more bullet-proof, but there is no Request class in CodeIgniter and that is just yet another bloody thing I have to do first.

This does not, and WILL NOT ever support any OAuth 1 providers. For that use [codeigniter-oauth](https://github.com/calvinfroedge/codeigniter-oauth).

## Usage Example

http://example.com/auth/session/facebook

```php
public function session($provider)
{
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