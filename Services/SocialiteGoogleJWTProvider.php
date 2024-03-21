<?php

namespace Modules\Iprofile\Services;

use Exception;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

class SocialiteGoogleJWTProvider extends GoogleProvider
{
  /**
   * Get the user for the given access token.
   *
   * @param string $token
   * @return \Laravel\Socialite\Two\User
   */
  public function userFromToken($token)
  {
    // Fetch the Google public keys
    $jwkset = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v3/certs'), true);
    $keys = JWK::parseKeySet($jwkset);
    try {
      $userData = (array)JWT::decode($token, $keys, ['RS256']);// Decode the ID token
      return $this->mapUserToObject($userData);
    } catch (\Exception $e) {
      throw new Exception('Failed parse Google credentials: ' . $e->getMessage(), 500);
    }
  }

  /**
   * Map the raw user array to a Socialite User instance.
   *
   * @param array $user
   * @return \Laravel\Socialite\Two\User
   */
  protected function mapUserToObject(array $user)
  {
    // You can modify the returned fields if needed
    return (new User)->setRaw($user)->map([
      'id' => $user['sub'],
      'nickname' => $user['name'],
      'name' => $user['name'],
      'email' => $user['email'],
      'avatar' => $user['picture'],
      'token' => $user['jti']
      // any other fields you need
    ]);
  }
}
