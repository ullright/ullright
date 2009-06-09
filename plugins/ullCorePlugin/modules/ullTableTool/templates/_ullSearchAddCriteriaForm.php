<h3><?php echo __('Add new criteria', null, 'common') ?></h3>
<form id="addCriteriaForm"
	action="<?php echo url_for($moduleName . '/addSearchCriteria') ?>"
	method="post">
<div class="edit_container color_light_bg">
<table>
<?php
$widget = $addCriteriaForm->offsetGet('columnSelect');
echo '<tr>';
echo '<td>' . $widget->renderLabel() . '</td>';
echo '<td>' . $widget->render() . '</td>';
echo '<td>' . $widget->renderError() . '</td>';
echo '<td>' . submit_tag(__('Add', null, 'common')) . '</td>';
echo '</tr>'
?>
</table>
</div>
</form>
