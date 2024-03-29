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
 * This behavior add automatic notification triggering on object modification.
 *
 * @package     sfPropelNotificationPlugin
 * @subpackage  behavior
 * @author      Tristan Rivoallan <tristan@rivoallan.net>
 */
class sfPropelNotificationBehavior
{

# ---- HOOKS

  /**
   * This hook to keep object "novelty" once it is saved. It also saves object's state before saving
   * it to enable later comparisons.
   *
   * @param    BaseObject    $object
   */
  public function presave(BaseObject $object)
  {
    if ($object->isNew())
    {
      $object->setWasNew(true);
    }

    // Save object state prior to saving
    $object->setBeforeSave($object);
  }

  /**
   * Object novelty is lost after an update.
   *
   * @param    BaseObject    $object
   */
  public function postUpdate(BaseObject $object)
  {
    $object->setWasNew(false);
  }

  /**
   * Triggers notification once the object has been successfuly saved to database.
   *
   * @param    BaseObject    $object
   */
  public function postSave(BaseObject $object)
  {
    include(sfConfigCache::getInstance()->checkConfig('config/sfPropelNotificationPlugin/types.yml'));
    $watch_types = array_keys(sfConfig::get('sfPropelNotificationPlugin_types'));

    foreach ($watch_types as $type)
    {
      sfPropelNotificationDispatcher::dispatch($type, $object);
    }
  }

# ---- PUBLIC API

  /**
   * Returns true if object was new before being saved to database and false if save was just about
   * updating the object.
   *
   * @param    BaseObject    $object
   * @return   bool
   */
  public function wasNew(BaseObject $object)
  {
    return $object->getWasNew();
  }

  /**
   * Returns true if object was new before being saved to database and false if save was just about
   * updating the object.
   *
   * @param    BaseObject    $object
   * @return   bool
   */
  public function getWasNew(BaseObject $object)
  {
    return isset($object->was_new) && (bool)$object->was_new;
  }

  /**
   * Sets object novelty status.
   *
   * @param    BaseObject    $object
   * @param    bool          $new
   */
  public function setWasNew(BaseObject $object, $new)
  {
    $object->was_new = (bool)$new;
  }

  /**
   * Saves object instance into "before_save" property. It can be called later (in notification
   * logic provider for instance) with getBeforeSave(). Useful for before / after comparisons.
   *
   * @param    BaseObject    $object
   */
  public function setBeforeSave(BaseObject $object)
  {
    $object->before_save = $object;
  }

  /**
   * Returns object instance as it was when the preSave() hook was called.
   *
   * @return    BaseObject    $object
   */
  public function getBeforeSave(BaseObject $object)
  {
    if (isset($object->before_save))
    {
      return $object->before_save;
    }
  }

}
