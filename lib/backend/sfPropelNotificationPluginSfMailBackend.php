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
 * This backend sends notification emails using the sfMail class.
 * 
 * @package     sfPropelNotificationPlugin
 * @subpackage  backend
 * @author      Tristan Rivoallan <tristan@rivoallan.net>
 * @see         http://www.symfony-project.com/trac/browser/doc/trunk/cookbook/email.txt
 */
class sfPropelNotificationPluginSfMailBackend extends sfPropelNotificationPluginAbstractBackend
{
  /**
   * Sends a notification email to user related to given watch.
   * 
   * The email body is built from the result of calling the sfPropelNotificationPluginBackends/SfMail module action.
   * 
   * This module should define a template for each of the supported notification type.
   * 
   * For instance, for a "new_user" type notification, the backend will look for a "NewUserSuccess.php" 
   * template in that module.
   * 
   * @param     Watch        $watch
   * @param     BaseObject   $object
   */
  public function notify(Watch $watch, BaseObject $object)
  {
    // class initialization
    $mail = new sfMail();
    
    // Mailer
    $mail->setMailer($this->getParameter('mailer', 'sendmail'));
    
    // Charset
    $mail->setCharset($this->getParameter('charset', 'utf-8'));      
      
    // From:
    $mail->setSender($this->getParameter('sender_name'), $this->getParameter('sender_address'));
    $mail->setFrom($this->getParameter('sender_name'), $this->getParameter('sender_address'));
  
    // To:
    $mail->addAddress($watch->getUser()->getEmail());
    
    // Subject:
    $subject = sprintf(sprintf('%s %s', $this->getParameter('subject_prefix'), $watch->getWatchType()));
    $mail->setSubject($subject);

    // Build email body
    $request = sfContext::getInstance()->getRequest();
    $request->setParameter('watch', $watch);
    $request->setParameter('object', $object);
    $controller = sfContext::getInstance()->getController();
    $txt_body = $controller->getPresentationFor('sfPropelNotificationPluginBackends', 'SfMail');
    $mail->setBody($txt_body);
    
    // Send the email
    $mail->send();
    
  }
}