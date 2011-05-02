<?php
/*
 *   *  This file is part of the sfOauthServerPlugin package.
 * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * */

/**
 * The default duration in seconds of the access token lifetime.
 */
define("sfOAUTH2SERVER_DEFAULT_ACCESS_TOKEN_LIFETIME", 3600); // 1 hour

/**
 * The default duration in seconds of the authorization code lifetime.
 */
define("sfOAUTH2SERVER_DEFAULT_AUTH_CODE_LIFETIME", 30);

/**
 * The default duration in seconds of the refresh token lifetime.
 */
define("sfOAUTH2SERVER_DEFAULT_REFRESH_TOKEN_LIFETIME", 1209600); // 336 hours


class sfOAuth2Server extends OAuth2 {

	private $scope;
	private $user_id;
	
	public function __construct()
	{
		$config = array('access_token_lifetime'=> sfOAUTH2SERVER_DEFAULT_ACCESS_TOKEN_LIFETIME,
						'auth_code_lifetime' => sfOAUTH2SERVER_DEFAULT_AUTH_CODE_LIFETIME,
						'refresh_token_lifetime' => sfOAUTH2SERVER_DEFAULT_REFRESH_TOKEN_LIFETIME);
						
		parent::__construct($config);
	}
	public function addClient($name,$description=NULL)
	{
		$consumer = new sfOauthServerConsumer();
		$consumer->setConsumerKey(sha1((time()/2).$name));
		$consumer->setConsumerSecret(sha1(md5(3*time().$name)));
		$consumer->setName($name);
		$consumer->setDescription($description);
		$consumer->setProtocole(2);
		$consumer->save();
		
		return $consumer;
	}
	
	protected function getRedirectUri($client_id) {
		if ($client_id==NULL)
		throw new OAuthException('invalid request');
	$q = Doctrine::getTable('sfOauthServerConsumer')->findOneByConsumerKey($client_id);
	if (!$q)
		return false;
	else
		return $q->getCallback();
		
	
	}
	
	 public function grantAccessToken($scope=NULL) {
			$this->scope = $scope;
			parent::grantAccessToken();
	 }
	 
	 public function setUserId($user_id)
	 {
			$this->user_id = $user_id;
	}

	 protected function checkClientCredentials($client_id, $client_secret = NULL) {
	  }
  
   /**
   * Implements OAuth2::getAccessToken().
   */
   
  protected function getAccessToken($oauth_token) {
 	$q = Doctrine::getTable('sfOauthServerAccessToken')->findOneByToken($oauth_token);
	if ($q)
		{
			return array('client_id' => $q->getConsumer()->getConsumerKey(),
						'expires' => $q->getExpires(),
						'scope' => $q->getScope());
						
		}
		else
			return NULL;
 
  }
  
  /**
   * Implements OAuth2::setAccessToken().
   */
  protected function setAccessToken($oauth_token, $client_id, $expires, $scope = NULL) {
		
		$consumer = Doctrine::getTable('sfOauthServerConsumer')->findOneByConsumerKey($client_id);
		if (!$consumer)
			throw new OAuthException('Invalid Request');
				
		$authtoken = new sfOauthServerAccessToken();
		$authtoken->setToken($oauth_token);
		$authtoken->setConsumer($consumer);
		$authtoken->setExpires($expires);
		$authtoken->setUserId($this->user_id);
		$authtoken->setScope($this->scope);
		$authtoken->setProtocole(2);
		$authtoken->save();		

  }
 
 
   /**
   * Overrides OAuth2::getSupportedGrantTypes().
   */
  protected function getSupportedGrantTypes() {
    return array(
      OAUTH2_GRANT_TYPE_AUTH_CODE,
    );
  }
  
  /**
   * Overrides OAuth2::getAuthCode().
   */
  protected function getAuthCode($code) {

	$q = Doctrine::getTable('sfOauthServerRequestToken')->findOneByToken($code);
	if ($q)
		{
			return array('client_id' => $q->getConsumer()->getConsumerKey(),
						'redirect_uri' => $q->getCallBack(),
						'expires' => $q->getExpires());
						
		}
		else
			return NULL;
  }
  
    /**
   * Overrides OAuth2::setAuthCode().
   */
  protected function setAuthCode($code, $client_id, $redirect_uri, $expires, $scope = NULL) {
		
		$consumer = Doctrine::getTable('sfOauthServerConsumer')->findOneByConsumerKey($client_id);
		if (!$consumer)
			throw new OAuthException('Invalid Request');
		
		$oauthCode = new sfOauthServerRequestToken();
		$oauthCode->setToken($code);
		$oauthCode->setConsumerId($consumer->getId());
		if ($redirect_uri==NULL)
			$redirect_uri = $consumer->getCallBack();
		$oauthCode->setCallBack($redirect_uri);
		$oauthCode->setUserId($this->user_id);
		$oauthCode->setExpires($expires);
		$oauthCode->setScope($scope);
		$oauthCode->setProtocole(2);
		$oauthCode->save();		
    }
}
