<?php
/*
 * 
  This file is part of the sfOauthServerPlugin package.
 * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 *  * Filter executed before each action secured by OAuth
 * It checks if request are correct and if the consumer has credentials to access to this action
 * @see sfBasicSecurityFilter
 */

class sfOauthFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
	//load oauth configuration
	$actionInstance = $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance();
	$sfoauth = new sfOauth($this->context,$actionInstance->getModuleName(),$actionInstance->getActionName());
	
	$request  =  $this->context->getRequest();
	
	if ($request->getParameter('oauth_version',NULL)=="1.0") // OAuth 1.0
	{
	    $oauthServer = new sfoauthserver(new sfOAuthDataStore());
	    $req = OAuthRequest::from_request();
	    $oauthServer->verify_request($req);

		
	 }
	else if ($request->getParameter('oauth_token',NULL)!=NULL) // OAuth 2.0
	{
	      $oauth = new sfOauth2Server();
	      $oauth->verifyAccessToken();
	}
	else
	{
		throw new OAuthException('Unauthorized Access');
	}

	
	$token = $request->getParameter('access_token',$request->getParameter('oauth_token'));
	$sfToken = Doctrine::getTable('sfOauthServerAccessToken')->findOneByToken($token);
	$user = $sfToken->getUser(); // Select user concerned

	$consumer = $sfToken->getConsumer();
	$consumer->increaseNumberQuery();
	$request->setParameter('sfGuardUser',$user); // save this user in a parameter 'user'
	$request->setParameter('sfOauthConsumer',$consumer); // save consumer in a parameter 'consumer'
    $credential = $sfoauth->getOauthCredential();

    if (null !== $credential && !$sfToken->hasCredential($credential)) // chek if the consumer is allowed to access to this action
		throw new OAuthException('Unauthorized Access');

    // this aplpication has access, continue
    $filterChain->execute();
  }  
}
