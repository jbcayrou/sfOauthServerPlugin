<?php

/**
 * PluginsfOauthServerConsumer form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginsfOauthServerConsumerForm extends BasesfOauthServerConsumerForm
{
  /**
   * @see sfForm
   */
  public function setup()
  {
    parent::setup();
    
    unset($this['created_at'], $this['updated_at'], $this['consumer_key'], $this['consumer_secret']);
    $this->setWidget('protocole', new sfWidgetFormSelect(array('choices' => array(1 => 'oauth 1.0', 2 => 'oauth 2.0'))));


    $this->validatorSchema['protocole'] = new sfValidatorChoice(array('choices' => array(1, 2)));
  }

}
