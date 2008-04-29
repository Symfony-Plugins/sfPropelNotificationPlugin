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
 * This class holds the `dispatch` method, backbone of the notification system.
 * 
 * @package     sfPropelNotificationPlugin
 * @subpackage  util
 * @author      Tristan Rivoallan <tristan@rivoallan.net>
 * 
 * @see         http://www.symfony-project.com/book/trunk/19-Mastering-Symfony-s-Configuration-Files
 */
class sfPropelNotificationDispatcher
{

  /**
   * Gets all subscriptions for requested watch_type, finds out if notification should be triggered,
   * then runs notification using whatever backends subscribing users configured.
   * 
   * @param    string       $watch_type
   * @param    BaseObject   $object
   */
  public static function dispatch($watch_type, BaseObject $object)
  {
    $c = new Criteria();
    $c->add(WatchPeer::WATCH_TYPE, $watch_type);
    $watches = WatchPeer::doSelect($c);
    foreach ($watches as $watch)
    {
      if (self::isWorthNotifying($object, $watch))
      {      
        foreach ($watch->getNotifiers() as $notifier)
        {
          $backend = self::getNotificationBackendInstance($notifier);
          $backend->notify($watch, $object);
        }
      }
    }
  }

  /**
   * Returns a configured backend instance, ready to launch notification.
   * 
   * @param     string     $notifier
   * @return    sfPropelNotificationPluginAbstractBackend
   */
  private static function getNotificationBackendInstance($notifier)
  {
    include(sfConfigCache::getInstance()->checkConfig('config/sfPropelNotificationPlugin/backends.yml'));
    $backends = sfConfig::get('sfPropelNotificationPlugin_backends');

    if (!isset($backends[$notifier]))
    {
      $msg = sprintf('Unknown notification backend "%s"', $notifier);
      throw new Exception($msg);
    }
    
    $class = $backends[$notifier]['class'];
    $instance = new $class();
    $instance->configure($backends[$notifier]['params']);
    
    return $instance;
  }

  /**
   * Tells object recent modification should trigger notification for requested type.
   * 
   * @param       BaseObject     $object
   * @param       BaseObject     $watch
   * @return      bool
   */
  private static function isWorthNotifying(BaseObject $object, BaseObject $watch)
  {
    include(sfConfigCache::getInstance()->checkConfig('config/sfPropelNotificationPlugin/types.yml'));
    $types = sfConfig::get('sfPropelNotificationPlugin_types');

    if (!isset($types[$watch->getWatchType()]))
    {
      $msg = sprintf('Unknown notification type "%s"', $watch->getWatchType());
      throw new Exception($msg);
    }
    
    return call_user_func(array($types[$watch_type]['logic_class'], 
                                sfInflector::camelize($watch->getWatchType())), $object, $watch);
  }

}
?>
