<?php
/**
 * ullflow header action icons
 *
 * partial printing the header action icons like edit, delete, ....
 * 
 * @package    ull_at
 * @subpackage ullFlow
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>

<?php if ($access): ?>
 
<ul class='ull_flow_headfoot_action_icons'>
   
  <li>
    <?php echo ull_icon('ullFlow/edit?doc=' . $ull_flow_doc->getId(), 'edit', __('Edit', null, 'common')); ?>
  </li>
  
  <li>
    <?php echo ull_icon(
                  'ullFlow/delete?doc=' . $ull_flow_doc->getId(),
                  'delete',
                  __('Delete', null, 'common'),
                  'confirm='.__('Are you sure?', null, 'common')
                ); ?>
  </li>
</ul>

<?php endif; ?>
