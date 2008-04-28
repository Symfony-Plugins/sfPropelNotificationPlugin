<?php
/*
 * This file is part of the sfPropelNotificationPlugin package.
 * 
 * (c) 2006-2007 Tristan Rivoallan <tristan@rivoallan.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Notification backends must extend this class. It takes care of all the configuration logic.
 * 
 * @package     sfPropelNotificationPlugin
 * @subpackage  backend
 * @author      Tristan Rivoallan <tristan@rivoallan.net>
 */
abstract class sfPropelNotificationPluginAbstractBackend
{
  
  private $parameter_holder;
  
  /**
   * Class constructor.
   */
  public function __construct()
  {
    $this->parameter_holder = new sfParameterHolder();
  }
  
  /**
   * Defines instance parameters.
   * 
   * @param   array    $parameters
   */
  public function configure($parameters)
  {
    $this->getParameterHolder()->add($parameters);
  }
  
  /**
   * Returns value of requested parameter or default value if the parameter is not set.
   * 
   * @param    string    $name
   * @param    mixed     $default
   * @return   mixed
   */
  public function getParameter($name, $default = null)
  {
    return $this->getParameterHolder()->get($name, $default);
  }

  /**
   * Sets value of given parameter.
   * 
   * @param     string    $name
   * @param     mixed     $value
   */
  public function setParameter($name, $value)
  {
    return $this->getParameterHolder()->set($name, $value);
  }

  /**
   * Returns instance parameter holder.
   * 
   * @return   sfParameterHolder
   */
  public function getParameterHolder()
  {
    return $this->parameter_holder;
  }

  /**
   * Triggers notification.
   * 
   * @param   Watch       $watch    The watch for which notification is requested
   * @param   BaseObject  $object   The object that triggered notification
   * 
   * @abstract   This method must be implemented by concrete backends
   */
  abstract function notify(Watch $watch, BaseObject $object);
  
}