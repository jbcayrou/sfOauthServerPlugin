<?php
/*
 *  This file is part of the sfOauthServerPlugin package.
 * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * 
 * This class allows to make a link with the file oauth.yml
 * 
 * This code is in part extracted and adapted from sfAction.class.php file of Symfony
 * Thank to Fabien Potencier and Sean Kerr for their work.
 * 
 */

class sfOauth
{
	
  public
    $oauth = array();
   protected $actionName;
   protected $context;
    
    
   /**
   *
   * @param sfContext $context    The current application context.
   * @param string    $moduleName The module name.
   * @param string    $actionName The action name.
   */
  public function __construct($context, $moduleName,$actionName)
  {
	  $this->context = $context;
	  $this->actionName=$actionName;
    // include security configuration
    if ($file = $context->getConfigCache()->checkConfig('modules/'. $moduleName.'/config/oauth.yml', true))
    {
      require($file);
    }

  }
  
    /*
     * If an Exception with a OauthException Type is throw, this method treats it.
     * */
	public function ExceptionHandler(sfEvent $event)
	{
		$exception = $event->getSubject();
		if (get_class($exception)=='OAuthException')
		{
			$response = sfContext::getInstance()->getResponse();
			$response->setContentType('text/html');
		
			$request = sfContext::getInstance()->getRequest();
			
			$format = $request->getRequestFormat();
			if (!$format)
				$format = 'html';

		      if ($mimeType = $request->getMimeType($format))
				{
				$response->setContentType($mimeType);
				}

      		if ($format=='json')
				$response->setContentType('text/javascript'); // By default, symfony set 'application/json' and the response is not show in the brower
				
			$response->sendHttpHeaders();


			 $template = sprintf('error.%s.php', $format);
			 $path =   dirname(__FILE__).'./../config/error/'.$template;
			 
			 $type = get_class($exception);
			 $message = $exception->getMessage();
			 include $path;

			return true;
		}
	}
	
  /*
   * Connect exceptions to exceptionHandler
   * */
  public function connectEvent()
  {
	$dispatcher = $this->context->getEventDispatcher();
	$dispatcher->connect('application.throw_exception', array($this,'ExceptionHandler'));
  }
    /**
   * Returns the oauth configuration for this module.
   *
   * @return string Current aouth configuration as an array
   */
   
  public function getOauthConfiguration()
  {
    return $this->oauth;
  }

  /**
   * Overrides the current oauth configuration for this module.
   *
   * @param array $oauth The new security configuration
   */
  public function setOauthConfiguration($oauth)
  {
    $this->oauth = $oauth;
  }
  
    /**
   * Returns a value from oauth.yml.
   *
   * @param string $name    The name of the value to pull from oauth.yml
   * @param mixed  $default The default value to return if none is found in oauth.yml
   *
   * @return mixed
   */
  public function getOauthValue($name, $default = null)
  {
    $actionName = strtolower($this->actionName);

    if (isset($this->oauth[$actionName][$name]))
    {
      return $this->oauth[$actionName][$name];
    }

    if (isset($this->oauth['all'][$name]))
    {
      return $this->oauth['all'][$name];
    }

    return $default;
  }
  
    /**
   * Indicates that this action requires oauth authentification.
   *
   * @return bool true, if this action requires oauth authentification, otherwise false.
   */
  public function isOauthSecure()
  {
    return $this->getOauthValue('is_secure', false);
  }
  
   /**
   * Gets credentials the application must have to access this action.
   *
   * @return mixed An array or a string describing the credentials the user must have to access this action
   */
  public function getOauthCredential()
  {
    return $this->getOauthValue('credentials');
  }
  
}

