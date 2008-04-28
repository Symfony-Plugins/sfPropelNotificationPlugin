<?php
/*
 * This file is part of the sfPropelNotificationPlugin package.
 * 
 * (c) 2006-2007 Tristan Rivoallan <tristan@rivoallan.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use_helper('I18N')

?>

<h2><?php echo __('My notifications') ?></h2>

<?php echo form_tag('sfPropelNotificationPluginNotifications/my') ?>

  <?php foreach (array_keys($watch_types) as $watch_type): ?>
  
    <h3><?php echo __($watch_type) ?></h3>
  
    <?php foreach (array_keys($watch_notifiers) as $watch_notifier): ?>
      
      <?php $checkbox_name = sprintf('%s[]', $watch_type) ?>
      <?php echo checkbox_tag($checkbox_name, 
                              $watch_notifier,
                              WatchHasWatchNotifierPeer::isBoundto($watch_notifier, $watch_type, $sf_user->getId())) ?>
      <label for="<?php echo $checkbox_name ?>"><?php echo __($watch_notifier . ':') ?></label>
      <br class="clearleft" />
    
    <?php endforeach; ?>
  
  <?php endforeach; ?>

  <?php echo submit_tag() ?>

</form>