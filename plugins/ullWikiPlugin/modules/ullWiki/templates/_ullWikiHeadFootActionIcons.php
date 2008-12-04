<?php
/**
 * ullwiki header action icons
 *
 * partial printing the header action icons like edit, delete, ....
 * 
 * @package    ull_at
 * @subpackage ullwiki
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>

<?php if ($access): ?>

<ul class='ullwiki_headfoot_action_icons'>

  <li>
    <?php echo ull_reqpass_icon(
            array('module' => 'ullWiki', 'action' => 'edit', 'docid' => $doc->id)
            , 'edit'
            , __('Edit', null, 'common')
          ); ?>
  </li>

  <li>
    <?php echo ull_reqpass_icon(
            array('module' => 'ullWiki', 'action' => 'delete', 'docid' => $doc->id)
            , 'delete'
            ,  __('Delete', null, 'common')
            , 'confirm='.__('Are you sure?', null, 'common')
          ); ?>
  </li>
</ul>

<?php endif; ?>
