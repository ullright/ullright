<h3><?php echo __('Add new criteria', null, 'common') ?></h3>
<form id="addCriteriaForm"
	action="<?php echo url_for($moduleName . '/addSearchCriteria') ?>"
	method="post">
<div class="edit_container color_light_bg">
<table>
<?php
echo $addCriteriaForm;
?>
</table>
<?php
echo submit_tag(__('Add', null, 'common'));
?></div>
</form>
