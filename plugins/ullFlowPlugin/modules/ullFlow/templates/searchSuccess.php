<?php echo $breadcrumb_tree ?>
<?php
$formUrl = 'ullFlow/search';

if (isset($app) && $app != null)
{
  $appSlug = $app->slug;
  $appLabel = $app->label;

  $formUrl .= '?app=' . $appSlug;
}

$title = __('Advanced search', null, 'common') . ' - ' . __('Workflow', null, 'common');
if (isset($appLabel))
{
  $title .= ' - ' . __($appLabel);
}
?>
<h1><?php echo $title ?></h1>

<?php if ($searchForm->getGenerator()->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $searchForm->getGenerator()->getForm()->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>
 
<?php

include_partial('ullTableTool/ullSearchCriterionForm',
  array('formUrl' => $formUrl,
        'form' => $searchForm->getGenerator()->getForm(),
        'moduleName' => $this->moduleName,
        'addCriteriaForm' => $addCriteriaForm));
?>