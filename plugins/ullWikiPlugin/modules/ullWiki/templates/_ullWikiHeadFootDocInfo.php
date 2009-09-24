<?php
/**
 * ullWiki header/footer docinfo partial
 *
 * expects an UllWiki object and prints the show footer
 * 
 * @package    ullright
 * @subpackage ullWiki
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>

<?php $user_widget = $sf_data->getRaw('user_widget') ?>

<div class='ull_wiki_headfoot_float_left'>
  <ul class='ull_wiki_headfoot_ul'>
    <li><?php echo __('Created by', null, 'common').' '
      . $user_widget->render(null, $doc->creator_user_id) .
      ', '.ull_format_datetime($doc->created_at); ?></li>
    <li><?php echo __('Updated by', null, 'common').' '
      . $user_widget->render(null, $doc->updator_user_id) .
      ', '.ull_format_datetime($doc->updated_at); ?></li>
    <li><?php echo __('Access level').': '
      . $doc->UllWikiAccessLevel->name ?></li>
    <?php if ($doc->duplicate_tags_for_search): ?>            
      <li><?php  echo __('Tags', null, 'common').': '
        . $doc->duplicate_tags_for_search ?></li>
    <?php endif ?>      

  </ul>
</div>