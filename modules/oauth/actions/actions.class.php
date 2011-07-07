<?php
	
class oauthActions extends sfActions
{

	public function preExecute()
	{
		$sfoauth = new sfOauth(sfContext::getInstance(),$this->getModuleName(),$this->getActionName());
		$sfoauth->connectEvent();
		sfConfig::set('sf_web_debug',false);
	}
	
	/*
	 *Recup RequestToken OAuth 1.0
	 */
	public function executeRequestToken(sfWebRequest $request)
	{	
		$oauthServer = new sfOauthServer(new sfOAuthDataStore());
		$req = OAuthRequest::from_request(NULL,$request->getUri());
		$this->token = $oauthServer->fetch_request_token($req);

		$this->setTemplate('token');
        return sfView::SUCCESS;
	}
		
	 /*
	 *Get AccessToken OAuth 1.0 and OAuth 2.0
	 */
		public function executeAccessToken(sfWebRequest $request)
	{

		if ($request->getParameter('oauth_version')=='1.0')
		{
			$oauthServer = new sfoauthserver(new sfOAuthDataStore());
			$req = OAuthRequest::from_request();

			$q = Doctrine::getTable('sfOauthServerRequestToken')->findOneByToken($request->getParameter('oauth_token'));
			$this->token = $oauthServer->fetch_access_token($req);
		
			if ($q->getUserId()==NULL)
			  throw new OAuthException('Token unauthorized');

			//return $this->setTemplate('token');
			 return $this->renderText(json_encode($this->token));
		}
		else
		{
			$q = Doctrine::getTable('sfOauthServerRequestToken')->findOneByToken($request->getParameter('code'));
			$oauthServer2 = new sfOauth2Server();
			$oauthServer2->setUserId($q->getUserId());
			$oauthServer2->grantAccessToken($q->getScope());
			return sfView::NONE;
		}
	}

	
}
