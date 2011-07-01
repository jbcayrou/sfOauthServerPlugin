<?php 
/*
  *  This file is part of the sfOauthServerPlugin package.
 * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * */

class sfOAuthServer extends OAuthServer {
	
	public function __construct($data_store)
	{
		parent::__construct($data_store);
		$hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->add_signature_method($hmac_method);
		$this->timestamp_threshold= 300; // a token (in requestToken) expires before 300 secondes
	
	}
	
	public function get_signature_methods() {
	return $this->signature_methods;
	}
	
	public function addClient($name,$description=NULL)
	{
		$consumer = new sfOauthServerConsumer();
		$consumer->setConsumerKey(sha1((time()/2).$name));
		$consumer->setConsumerSecret(sha1(md5(3*time().$name)));
		$consumer->setName($name);
		$consumer->setDescription($description);
		$consumer->save();
		
		return $consumer;
	}
	
	static function generateConsumerKey($name="")
	{
		return sha1((time()/2).$name);
	} 
	
	static function generateConsumerSecret($name="")
	{
		return sha1((time()/1337).md5($name));
	} 
	/* Check if all params are corrects
	 * @return boolean
	 * */
	public function checkAuthorizeRequest(sfWebRequest $request)
	{
		$sfToken = Doctrine::getTable('sfOauthServerRequestToken')->findOneByToken($request->getParameter('oauth_token'));
		if (!$sfToken)
				return false;	
		return true;
	}
	/* Authorize a token ( Request token)
	 * @param string $token
	 * @param integer $userId
	 * */
	public function authorizeToken($token,$userId)
	{
		$sfToken = Doctrine::getTable('sfOauthServerRequestToken')->findOneByToken($token);
		$sfToken->setUserId($userId);
		$sfToken->save();
		
		Doctrine::getTable('SfOauthServerUserScope')->authorizeApplication($sfToken->getConsumerId(),$userId,$sfToken->getScope());
	}
	
}

class sfOAuthDataStore extends OAuthDataStore {

    function lookup_consumer($consumer_key) {/*{{{*/
		$q = Doctrine::getTable('sfOauthServerConsumer')->findOneByConsumerKey($consumer_key);
		if ($q)
		{
			$consumer = new OAuthConsumer($q->getConsumerKey(),$q->getConsumerSecret(),$q->getCallback());
			return $consumer;
		}
		else
			return NULL;
    }

    function lookup_token($consumer, $token_type, $token) {
		if ($token_type=='request')
			$q = Doctrine::getTable('sfOauthServerRequestToken')->findOneByToken($token);
		else if ($token_type=='access')
			$q = Doctrine::getTable('sfOauthServerAccessToken')->findOneByToken($token);
		if ($q)
		{
			$token = new OAuthToken($q->getToken(),$q->getSecret());
			return $token;
		}
		else
			return NULL;
    }

    function lookup_nonce($consumer, $token, $nonce, $timestamp) {
		$q = Doctrine::getTable('sfOauthServerNonce')->findOneByNonce($nonce);
		if ($q)
			return true;
		else
		{
			$sfNonce = new sfOauthServerNonce();
			$sfNonce->setNonce($nonce);
			$sfNonce->save();
			
			return false;
		}
    }

  function new_request_token($consumer, $callback = null) {
		
		$c = Doctrine::getTable('sfOauthServerConsumer')->findOneByConsumerKey($consumer->key);
	  
	    $key = md5(time());
		$secret = time() + time();
		$token = new OAuthToken($key, md5(md5($secret)));
		
		$token = new sfOauthServerRequestToken();
		$token->setToken($key);
		$token->setSecret(md5(md5($secret)));
		$token->setConsumer($c);
		$token->setScope($c->getScope());
		$token->save();
		
		$token = new OAuthToken($token->getToken(),$token->getSecret());
		return $token;
    }

  function new_access_token($token, $consumer, $verifier = null) {
		
		$c = Doctrine::getTable('sfOauthServerConsumer')->findOneByConsumerKey($consumer->key);
		$token = Doctrine::getTable('sfOauthServerRequestToken')->findOneByToken($token->key);
		$key = md5(time());
		$secret = time() + time();
		$accesstoken = new OAuthToken($key, md5(md5($secret)));
		
		$accesstoken = new sfOauthServerAccessToken();
		$accesstoken->setToken($key);
		$accesstoken->setSecret(md5(md5($secret)));
		$accesstoken->setConsumer($c);
		$accesstoken->setUserId($token->getUserId());
		$accesstoken->setScope($token->getScope());
		$accesstoken->save();
		
		$accesstoken = new OAuthToken($accesstoken->getToken(),$accesstoken->getSecret());
		
		return $accesstoken;
    }
}
