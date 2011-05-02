<?php

class sfOauthCleaTokensTask extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application',null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace = 'oauth';
    $this->name = 'clear-tokens';
    $this->briefDescription = 'Clear nonces table';

    $this->detailedDescription = <<<EOF
The [guard:add-group|INFO] task cleans odd nonces:

  [./symfony oauth:clear-nonce|INFO]

EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $oauth2 = new sfOauth2Server();
    $accessTokenLife = $oauth2->getVariable('access_token_lifetime');
    $authCodeLife = $oauth2->getVariable('auth_code_lifetime');
    $expireRequestToken = 
	$this->logSection('oauth 2.0', sprintf('auth code aged over %d secondes will be deleted ...', $authCodeLife));
	$this->logSection('oauth 2.0', sprintf('access tokens aged over %d secondes will be deleted ...', $accessTokenLife));
	
	$tokens = Doctrine::getTable('sfOauthServerRequestToken')->findAll();

	foreach ($tokens as $token){
	  if (($token->getProtocole()=='2'&&$token->getExpires()>$authCodeLife) || ($token->getProtocole()=='1'&&(time() - strtotime($token->getCreated_at())>300)))
	    $token->delete();
	  
	}
	$this->logSection('oauth', sprintf('%d auth tokens have been deleted', $tokens->count()));


	$tokens = Doctrine::getTable('sfOauthServerAccessToken')->createQuery('t')->where('t.expires > ?',$accessTokenLife)->execute();
	foreach ($tokens as $token){
	  $token->delete();
	}
	$this->logSection('oauth', sprintf('%d access tokens have been deleted', $tokens->count()));
  }
}
