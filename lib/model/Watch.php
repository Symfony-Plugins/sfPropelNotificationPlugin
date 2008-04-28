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
 * Subclass for representing a row from the 'watch' table. A "watch" represents the relationship between
 * a user and a notification type.
 *
 * @package         sfPropelNotificationPlugin
 * @subpackage      model
 * @author          Tristan Rivoallan <tristan@rivoallan.net>
 */ 
class Watch extends BaseWatch
{

  /**
   * Adds a notification backend to the watch.
   * 
   * @param    string    $notifier
   */
  public function addWatchNotifier($notifier)
  {
    if (!$this->hasWatchNotifier($notifier))
    {
      $rel = new WatchHasWatchNotifier();
      $rel->setWatch($this);
      $rel->setWatchNotifier($notifier);
      $rel->save();
    }
  }

  /**
   * Removes a notification backend from the watch.
   * 
   * @param    string    $notifier
   */
  public function removeWatchNotifier($notifier)
  {
    $c = new Criteria();
    $c->add(WatchHasWatchNotifierPeer::WATCH_ID, $this->getId());
    $c->add(WatchHasWatchNotifierPeer::WATCH_NOTIFIER, $notifier);
    WatchHasWatchNotifierPeer::doDelete($c);
  }

  /**
   * Returns true if watch is already related to given notifier.
   * 
   * @param    string    $notifier
   * @return   bool
   */
  public function hasWatchNotifier($notifier)
  {
    $c = new Criteria();
    $c->add(WatchHasWatchNotifierPeer::WATCH_ID, $this->getId());
    $c->add(WatchHasWatchNotifierPeer::WATCH_NOTIFIER, $notifier);
    
    return (bool)WatchHasWatchNotifierPeer::doSelectOne($c);
  }

  /**
   * Returns all watch's notifiers.
   * 
   * @return   array 
   */
  public function getNotifiers()
  {
    $notifiers = array();
    
    $c = new Criteria();
    $c->add(WatchHasWatchNotifierPeer::WATCH_ID, $this->getId());
    
    if ($rels = WatchHasWatchNotifierPeer::doSelect($c))
    {
      foreach ($rels as $rel)
      {
        $notifiers[] = $rel->getWatchNotifier();   
      }
    }
    
    return $notifiers;
  }

  public function delete($con = null)
  {
    $c = new Criteria();
    $c->add(WatchHasWatchNotifierPeer::WATCH_ID, $this->getId());
    WatchHasWatchNotifierPeer::doDelete($c);

    parent::delete($con);
  }

}
