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

<?php $user_widget = $sf_data->getRaw('user_widget') ?>

<div id="ull_flow_edit_header">
	<h1><?php echo $doc ?></h1>
	<div id="ull_flow_edit_header_list_container">
		<ul class="ull_flow_edit_header_list">
		  <li>
		    <?php echo __('Created by', null, 'common') ?>
		    <?php echo $user_widget->render(null, $doc->creator_user_id) ?>
		    (<?php echo ull_format_datetime($doc->created_at); ?>)
		  </li>
		  <li>
		    <?php echo __('Last action')?>:
		    <?php echo $doc->UllFlowAction ?>
		    <?php if ($doc->UllFlowAction->is_show_assigned_to): ?>
		      <?php echo $user_widget->render(null, $doc->assigned_to_ull_entity_id) ?>
		    <?php endif ?>
		    <?php echo __('by') ?>
		    <?php echo $user_widget->render(null, $doc->updator_user_id) ?>
		    -
		    <?php echo __('Last updated on', null, 'common')?> 
		    <?php echo ull_format_datetime($doc->updated_at); ?>
		  </li>
		  <?php if ($doc->UllFlowAction->is_in_resultlist): //excludes action "closed" ?>
		    <li>
		      <?php echo __('Next one') ?>:
		      <?php echo $user_widget->render(null, $doc->assigned_to_ull_entity_id) ?>
		      (<?php echo __('Step') ?>
		      <?php echo $doc->UllFlowStep ?>)
	      </li>
	    <?php endif ?>
		</ul>
	</div>
</div>