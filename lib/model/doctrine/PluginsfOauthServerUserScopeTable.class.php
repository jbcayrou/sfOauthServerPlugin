<?php
  /**
   *  This file is part of the sfOauthServerPlugin package.
   * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
   * For the full copyright and license information, please view the LICENSE
   * file that was distributed with this source code.
   * PluginsfOauthServerUserScopeTable
   * 
   * This class has been auto-generated by the Doctrine ORM Framework
   */
class PluginsfOauthServerUserScopeTable extends Doctrine_Table
{
  /**
   * Returns an instance of this class.
   *
   * @return object PluginsfOauthServerUserScopeTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('PluginsfOauthServerUserScope');
  }
  /**
   * To save when an user accept an application
   * @param integer $consumerId
   * @param integer $userId
   * @param string $scope
   */
  public function authorizeApplication($consumerId,$userId,$scope)
  {
    $userScope = $this->createQuery('c')->where('c.consumer_id = ?' ,$consumerId)
      ->andWhere('c.user_id = ?',$userId)
      ->fetchOne();
    if (!$userScope){
      $userScope = new sfOauthServerUserScope();
      $userScope->setConsumerId($consumerId);
      $userScope->setUserId($userId);
    }
    $userScope->setScope($scope);
    $userScope->save();
			
  }
  /**
   * Check if an application has already been accepted by an user
   * @param integer $consumerId
   * @param integer $userId
   * @param string $scope
   * @return boolean
   */
  public function isApplicationAuthorized($consumerId,$userId,$scope)
  {
	 if (!$scope) // If an application has no permission, it is automatically authorized
		return true;
		
    $userScope = $this->createQuery('c')->where('c.consumer_id = ?' ,$consumerId)
      ->andWhere('c.user_id = ?',$userId)
      ->fetchOne();
    if(!$userScope)
      return false;
    $permissions = explode($userScope->getScope(),' ');
    $scope = explode($scope,' ');
		
    if(array_diff($permissions,$scope)==Array() )
      return true;
    else
      return false;
		
  }

  public function getApplicationsOf($userId)
  {
    $q = $this->createQuery('a')->where('a.user_id = ?',$userId)->leftJoin('a.Consumer')->execute();
    return $q;
  }
 
}
