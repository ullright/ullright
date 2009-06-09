<?php include_partial('ullTableTool/jQueryRequirements')?>
<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>
<h1><?php echo __('Advanced search', null, 'common') . ' - ' . __('User', null, 'common') ?></h1>

<?php if ($searchForm->getGenerator()->getForm()->hasErrors()): ?>
	<div class='form_error'><?php echo __('Please correct the following errors', null, 'common') ?>:
	<?php echo $searchForm->getGenerator()->getForm()->renderGlobalErrors() ?>
	</div>
	<br /><br />
<?php endif; ?>

<?php
$formUrl = 'ullUser/search';

include_partial('ullTableTool/ullSearchCriterionForm',
  array('formUrl' => $formUrl,
        'form' => $searchForm->getGenerator()->getForm(),
        'moduleName' => $this->moduleName,
        'addCriteriaForm' => $addCriteriaForm));
?>