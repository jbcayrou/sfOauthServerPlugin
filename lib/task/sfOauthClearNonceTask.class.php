<?php


class sfOauthClearNonceTask extends sfBaseTask
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
    $this->name = 'clear-nonces';
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
    
	$nonceLife = sfConfig::get('app_oauth_nonce_expire', 60*10);

	$this->logSection('oauth', sprintf('nonces aged over %d secondes will be deleted ...', $nonceLife));
	
    $nonces = Doctrine_Core::getTable('sfOauthServerNonce')->findAll();
    $counter=0;
	foreach ($nonces as $nonce){
		$created_at = strtotime($nonce->getCreatedAt());
		$expire_at = $created_at + $nonceLife;
		if (time() > $expire_at){
			$nonce->delete();
			$counter++;
		}
	}
	 $this->logSection('oauth', sprintf('%d nonces have been deleted', $counter));
  }
}
