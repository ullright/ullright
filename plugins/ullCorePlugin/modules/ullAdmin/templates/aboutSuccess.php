<?php echo $breadcrumb_tree ?>

<h3><?php echo __('About', null, 'ullCoreMessages') ?></h3>

<p>Powered by the enterprise 2.0 platform 
<?php echo ull_link_to(
  'ullright', 
  'http://www.ullright.org', 
  'link_external=true'
) ?></p>

<p>
Copyright 2007-<?php echo date('Y')?> 
by <a href="mailto:klemens.ullmann-marx@ull.at">Klemens Ullmann-Marx</a>
/ <?php echo ull_link_to('ull.at', 'http://www.ull.at', 'ull_js_observer_confirm=true link_external=true')?>
</p>

<p>License: GPL2</p>

<p>Revision: <?php echo $revision ?></p>
