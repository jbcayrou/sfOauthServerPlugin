<?php
	
class sfOauthDeveloperActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->applications = $this->getUser()->getGuardUser()->getApplications();
  }
  public function executeShow(sfWebRequest $request)
  {
  $this->forward404Unless($this->application = Doctrine_Core::getTable('sfOauthServerConsumer')->find(array($request->getParameter('id'))), sprintf('Object sf_oauth_server_consumer does not exist (%s).', $request->getParameter('id')));
  if ($this->application->hasDeveloper($this->getUser()->getAttribute('user_id', null, 'sfGuardSecurityUser')))
      $this->isDeveloper= true;

  }
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new sfOauthServerConsumerForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    
    $this->form = new sfOauthServerConsumerForm();   
    $this->processForm($request, $this->form);
    
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($sf_oauth_server_consumer = Doctrine_Core::getTable('sfOauthServerConsumer')->find(array($request->getParameter('id'))), sprintf('Object sf_oauth_server_consumer does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($sf_oauth_server_consumer->hasDeveloper($this->getUser()->getAttribute('user_id', null, 'sfGuardSecurityUser')),sprintf('You are not allowed to edit this application'));
    $this->form = new sfOauthServerConsumerForm($sf_oauth_server_consumer);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($sf_oauth_server_consumer = Doctrine_Core::getTable('sfOauthServerConsumer')->find(array($request->getParameter('id'))), sprintf('Object sf_oauth_server_consumer does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($sf_oauth_server_consumer->hasDeveloper($this->getUser()->getAttribute('user_id', null, 'sfGuardSecurityUser')),sprintf('You are not allowed to edit this application'));
  $this->form = new sfOauthServerConsumerForm($sf_oauth_server_consumer);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($sf_oauth_server_consumer = Doctrine_Core::getTable('sfOauthServerConsumer')->find(array($request->getParameter('id'))), sprintf('Object sf_oauth_server_consumer does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($sf_oauth_server_consumer->hasDeveloper($this->getUser()->getAttribute('user_id', null, 'sfGuardSecurityUser')),sprintf('You are not allowed to edit this application'));
    $sf_oauth_server_consumer->delete();

    $this->redirect('sfOauthDeveloper/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    $isnew=$form->getObject()->isNew();
    if ($form->isValid())
    {
      $sf_oauth_server_consumer = $form->save();
      if ($isnew)
	$sf_oauth_server_consumer->addDeveloper($this->getUser()->getAttribute('user_id', null, 'sfGuardSecurityUser'));

      $this->redirect('sfOauthDeveloper/edit?id='.$sf_oauth_server_consumer->getId());
    }
  }
}
