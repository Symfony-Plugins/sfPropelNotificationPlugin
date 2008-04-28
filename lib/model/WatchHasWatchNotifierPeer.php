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
 * Subclass for performing query and update operations on the 'watch_has_watch_notifier' table.
 *
 * @package         sfPropelNotificationPlugin
 * @subpackage      model
 * @author          Tristan Rivoallan <tristan@rivoallan.net>
 */ 
class WatchHasWatchNotifierPeer extends BaseWatchHasWatchNotifierPeer
{

  /**
   * Returns true if all elements in triplet are related.
   * 
   * @param     string     $notifier
   * @param     string     $watch_type
   * @param     mixed      $user_id
   * 
   * @return    bool
   */
  public static function isBoundTo($notifier, $watch_type, $user_id)
  {
    $has_rel = false;
    
    $c = new Criteria();
    $c->add(WatchPeer::USER_ID, $user_id);
    $c->add(WatchPeer::WATCH_TYPE, $watch_type);
    if ($watch = WatchPeer::doSelectOne($c))
    {
      $c = new Criteria();
      $c->add(WatchHasWatchNotifierPeer::WATCH_ID, $watch->getId());
      $c->add(WatchHasWatchNotifierPeer::WATCH_NOTIFIER, $notifier);
      $has_rel = WatchHasWatchNotifierPeer::doSelectOne($c);
    }
    
    return (bool)$has_rel;
  }
  
}
