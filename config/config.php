<?php
/*
 * This file is part of the sfPropelNotificationPlugin package.
 * 
 * (c) 2006-2007 Tristan Rivoallan <tristan@rivoallan.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

sfPropelBehavior::registerHooks('sfPropelNotificationPlugin', array(
  ':save:pre'      => array('sfPropelNotificationBehavior', 'preSave'),
  ':save:post'     => array('sfPropelNotificationBehavior', 'postSave'),
  ':update:post'   => array('sfPropelNotificationBehavior', 'postUpdate'),
));

sfPropelBehavior::registerMethods('sfPropelNotificationPlugin', array(
  'wasNew'            => array('sfPropelNotificationBehavior', 'wasNew'),
  'setWasNew'         => array('sfPropelNotificationBehavior', 'setWasNew'),
  'getWasNew'         => array('sfPropelNotificationBehavior', 'getWasNew'),
));