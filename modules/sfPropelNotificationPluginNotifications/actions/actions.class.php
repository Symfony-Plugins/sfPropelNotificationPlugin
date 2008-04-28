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
 * Notifications management screens.
 * 
 * @package     sfPropelNotificationPlugin
 * @subpackage  actions
 * @author      Tristan Rivoallan <tristan@rivoallan.net>
 */
class sfPropelNotificationPluginNotificationsActions extends sfActions
{
  
  /**
   * This screen enables a logged in user to manage its subscriptions.
   */
  public function executeMy()
  {
    include(sfConfigCache::getInstance()->checkConfig('config/sfPropelNotificationPlugin/types.yml'));
    include(sfConfigCache::getInstance()->checkConfig('config/sfPropelNotificationPlugin/backends.yml'));
    $this->watch_types = sfConfig::get('sfPropelNotificationPlugin_types');
    $this->watch_notifiers = sfConfig::get('sfPropelNotificationPlugin_backends'); 
    
    if ($this->getRequest()->getMethod() == SfRequest::POST)
    {
      $watch_preferences = $this->getRequest()->getParameterHolder()->getAll();

      // Cleanup preferences array
      foreach (array('module', 'action', 'commit') as $k)
      {
        unset($watch_preferences[$k]);
      }
      
      // Retrieve connected user info from database
      $peer_class = $this->getUser()->getAttribute('peer_class', null, 'sfPropelNotificationPlugin');
      $user_id = $this->getUser()->getAttribute('uid', null, 'sfPropelNotificationPlugin');
      $user = call_user_func(array($peer_class, 'retrieveByPk'), $user_id);
      
      foreach ($user->getWatchs() as $watch)
      {
        $watch_type = $watch->getWatchType();
        
        // Unsubscriptions
        if (!isset($watch_preferences[$watch_type]) || !count($watch_preferences[$watch_type]))
        {
          $watch->delete();
        }
        
        // Subscription modification
        if (isset($watch_preferences[$watch_type]) && count($watch_preferences[$watch_type]))
        {
          foreach ($this->watch_notifiers as $notifier => $notifier_spec)
          {
            // Notifier add
            if (in_array($notifier, $watch_preferences[$watch_type]))
            {
              $watch->addWatchNotifier($notifier);
            }
            // Notifier removal
            else
            {
              if ($watch->hasWatchNotifier($notifier))
              {
                $watch->removeWatchNotifier($notifier);
              }
            }
          }
        }
        unset($watch_preferences[$watch_type]);        
      }
      
      // Remaining watch preferences must be added to watch
      foreach ($watch_preferences as $watch_type => $notifiers)
      {
        $watch = new Watch();
        $watch->setUser($user);
        $watch->setWatchType($watch_type);
        foreach ($notifiers as $notifier)
        {
          $watch->addWatchNotifier($notifier);
        }
        $watch->save();
      }
      
    }
  }
}