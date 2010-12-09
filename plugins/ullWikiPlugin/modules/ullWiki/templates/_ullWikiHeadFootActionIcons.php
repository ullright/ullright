<?php
/**
 * ullWiki header action icons
 *
 * partial printing the header action icons like edit, delete, ....
 * 
 * @package    ullright
 * @subpackage ullWiki
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>

<?php if ($access): ?>

<ul class='ull_wiki_headfoot_action_icons no_print'>

  <li>
    <?php
      echo ull_link_to(ull_image_tag('edit'),
            array('module' => 'ullWiki', 'action' => 'edit', 'docid' => $doc->id));
    ?>
  </li>

  <li>
    <?php
      echo ull_link_to(ull_image_tag('delete'), array('module' => 'ullWiki', 'action' => 'delete', 'docid' => $doc->id),
          'confirm='.__('Are you sure?', null, 'common')); 
   
      ?>
  </li>
</ul>

<?php else: ?>

&nbsp;

<?php endif; ?>