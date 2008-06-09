<?php echo $breadcrumbTree->getHtml(); 
//$sf_user->setCulture('de');
?>

<h1><?php echo __('Wiki') . ' ' . __('Home', null, 'common'); ?></h1>

<h4><?php echo __('Search', null, 'common'); ?></h4>
<ul>
  <li>
  <?php echo ull_reqpass_form_tag(array('action' => 'list')); ?>
  <?php echo input_tag('search'); ?>
  &nbsp; <?php echo submit_tag(__('Search', null, 'common')) ?>
  <br /><?php echo checkbox_tag('fulltext'); ?> 
  <?php echo __('Full text', null, 'common'); ?>
  </li>
</ul>
</form>

<h4><?php echo __('Actions', null, 'common'); ?></h4>
<ul>
  <li><?php echo link_to(__('Create', null, 'common'), 'ullwiki/create') ?></li>
</ul>

<h4><?php echo __('Queries', null, 'common'); ?></h4>
<ul>
  <li><?php echo ull_reqpass_link_to(__('New entries', null, 'common'), array('action' => 'list')) ?></li>
  <li><?php echo ull_reqpass_link_to(__('Ordered by subject', null, 'common'), array('action' => 'list', 'order' => 'SUBJECT')) ?></li>
</ul>

<?php
//$culture = $sf_user->getCulture();
//
//echo "culture: $culture";

?>
