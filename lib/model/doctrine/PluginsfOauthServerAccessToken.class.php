<?php
/*
 *  This file is part of the sfOauthServerPlugin package.
 * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Thank to Olivier Verdier for his method hasCredential ( sfBasicSecurityUser.class.php)
 * 
 * PluginsfOauthServerAccessToken
 * 
 */
abstract class PluginsfOauthServerAccessToken extends BasesfOauthServerAccessToken
{

    /**
   * Returns true if the token has credential.
   *
   * @param  mixed $credentials
   * @param  bool  $useAnd       specify the mode, either AND or OR
   * @return bool
   */
  public function hasCredential($credentials, $useAnd = true)
  {
	 $tokenCredentials = explode(" ", $this->getScope());
	  
    if (null === $tokenCredentials)
    {
      return false;
    }
	
    if (!is_array($credentials))
    {
      return in_array($credentials, $tokenCredentials);
    }

    // now we assume that $credentials is an array
    $test = false;

    foreach ($credentials as $credential)
    {
      // recursively check the credential with a switched AND/OR mode
      $test = $this->hasCredential($credential, $useAnd ? false : true);

      if ($useAnd)
      {
        $test = $test ? false : true;
      }

      if ($test) // either passed one in OR mode or failed one in AND mode
      {
        break; // the matter is settled
      }
    }

    if ($useAnd) // in AND mode we succeed if $test is false
    {
      $test = $test ? false : true;
    }

    return $test;
  
  }
  
}
