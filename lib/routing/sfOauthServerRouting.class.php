<?php
/*
 * This file is part of the sfOauthServerPlugin package.
 * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

/**
 * sfOauthServerRouting configuration.
 * 
 * @package    sfOauthServerPlugin
 * @subpackage routing
 * @author     Matthias Krauser <matthias@krauser.eu>
 */
class sfOauthServerRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   * @static
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    
    /* @var $r sfPatternRouting */

    // preprend our routes
    $r->prependRoute('sf_oauth_server_consumer_sfOauthAdmin', new sfDoctrineRouteCollection(array(
        'name'        => 'sf_oauth_server_consumer_sfOauthAdmin',
        'model'       => 'sfOauthServerConsumer', 
        'module'      => 'sfOauthAdmin',
        'prefix_path' => '/oauth/admin', 
        'with_wildcard_routes' => true
    )));
  }
}