<?php
/**
 * ullwiki header/footer docinfo partial
 *
 * expexts a ullwiki object and prints the show footer
 * 
 * @package    ull_at
 * @subpackage ullwiki
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>

<div class='ullwiki_headfoot_float_left'>
  <ul class='ullwiki_headfoot_ul'>
    <li><?php echo __('Created by', null, 'common').' '
      .Doctrine::getTable('UllUser')->find($doc->creator_user_id) .
      ', '.ull_format_datetime($doc->created_at); ?></li>
    <li><?php echo __('Updated by', null, 'common').' '
      .Doctrine::getTable('UllUser')->find($doc->updator_user_id) .
      ', '.ull_format_datetime($doc->updated_at); ?></li>
  </ul>
</div>

<!-- 
<div class='ullwiki_headfoot_float_left'>
  <ul class='ullwiki_headfoot_ul'>
    <li><?php echo __('DocId').': '.$doc->id; ?></li>
    <li><?php echo __('Version').': '.$doc->version; ?></li>
  </ul>
</div>
-->