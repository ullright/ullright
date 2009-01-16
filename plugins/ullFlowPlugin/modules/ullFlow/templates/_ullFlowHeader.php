<?php
/**
 * ull_flow header partial
 * 
 * @package    ullright
 * @subpackage ullFlow
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>

<div id="ull_flow_edit_header">
	<h1><?php echo $doc->UllFlowApp->doc_label ?> "<?php echo $doc ?>"</h1>
	<div id="ull_flow_edit_header_list_container">
		<ul class="ull_flow_edit_header_list">
		  <li>
		    <?php echo __('Created by', null, 'common') ?>
		    <?php echo $doc->Creator ?>
		    (<?php echo ull_format_datetime($doc->created_at) . ', ' .
		                    time_ago_in_words(strtotime($doc->created_at)); ?>)
		  </li>
		  <li>
		    <?php echo __('Last action')?>:
		    <?php echo $doc->UllFlowAction ?>
		    <?php if ($doc->UllFlowAction->is_show_assigned_to): ?>
		      <?php echo '<span class="ull_flow_memories_light">' . $doc->UllEntity . '</span>' ?>
		    <?php endif ?>
		    <?php echo __('by') ?>
		    <?php echo $doc->Updator ?>
		    (<?php echo ull_format_datetime($doc->updated_at) . ', ' .
		                    time_ago_in_words(strtotime($doc->updated_at)); ?>)
		  </li>
		  <li>
		    <?php if ($doc->UllFlowAction->is_in_resultlist): //excludes action "closed" ?>
		      <?php echo __('Next one') ?>:
		      <?php echo $doc->UllEntity ?>
		      (<?php echo __('Step') ?>
		      <?php echo $doc->UllFlowStep ?>)
		    <?php endif ?>
		  </li>
		</ul>
	</div>
</div>