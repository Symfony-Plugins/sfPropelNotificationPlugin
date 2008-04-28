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
 * Notification backends related actions. They are used by some backends to get MVC templating
 * (ie. emails body, jabber messages body, etc).  
 * 
 * @package     sfPropelNotificationPlugin
 * @subpackage  actions
 * @author      Tristan Rivoallan <tristan@rivoallan.net>
 */
class sfPropelNotificationPluginBackendsActions extends sfActions
{
  
  /**
   * Used by sfPropelNotificationPluginSfMailBackend to get email body depending on notification type.
   */
  public function executeSfMail()
  {
    $this->setLayout(false);
    $this->watch = $this->getRequestParameter('watch');
    $this->object = $this->getRequestParameter('object');
    $this->setTemplate(sfInflector::camelize($this->watch->getWatchType()));
  }

}