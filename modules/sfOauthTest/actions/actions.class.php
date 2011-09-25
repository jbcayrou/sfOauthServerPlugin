<?php


class sfOauthTestActions extends sfActions
{
  public function preExecute()
  {
	  sfConfig::set('sf_web_debug',false);
  }
  
  public function executeTest(sfWebRequest $request)
  {
	  $sfGuardUser = $request->getParameter('sfGuardUser');
	  $sfOauthConsumer = $request->getParameter('sfOauthConsumer');
	  $this->infos = array('userId' => ($sfGuardUser) ? $sfGuardUser->getId() : null,
							'consumerId' => $sfOauthConsumer->getId());
	//return $this->renderText(json_encode($this->infos));
  }
  
}
  
