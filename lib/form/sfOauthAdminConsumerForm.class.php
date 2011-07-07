<?php


class sfOauthAdminConsumerForm extends SfOauthServerConsumerForm
{
	public function configure()
	{
		unset($this['created_at'],$this['updated_at'],$this['consumer_key'],$this['consumer_secret']);
		$this->setWidget('protocole', new sfWidgetFormSelect(array('choices' => array(1=>'oauth 1.0',2 =>'oauth 2.0'))));

		$this->validatorSchema['protocole'] = new sfValidatorChoice(array('choices' => array(1,2)));
	}


}