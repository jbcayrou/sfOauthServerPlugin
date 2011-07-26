<?php
/*
 *  This file is part of the sfOauthServerPlugin package.
 * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

/**
 * sfOauthServerPluginConfiguration configuration.
 * 
 * @package    sfOauthServerPlugin
 * @subpackage config
 * @author     Matthias Krauser <matthias@krauser.eu>
 */
class sfOauthServerPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (sfConfig::get('app_sf_oauth_server_plugin_routes_register', true))
    {
      if(in_array('sfOauthAdmin', sfConfig::get('sf_enabled_modules', array())))
      {
        $this->dispatcher->connect('routing.load_configuration', array('sfOauthServerRouting', 'listenToRoutingLoadConfigurationEvent'));
      }
      
    }
  }
}
