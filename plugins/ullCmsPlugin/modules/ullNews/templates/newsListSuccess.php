<link rel="alternate" type="application/atom+xml" title="Latest Jobs"
  href="<?php echo url_for('/ullNews/newsListFeed', true) ?>" />
<div>
<h3>
  <?php echo __('News', null, 'common')?>
</h3>
<?php include_component('ullNews', 'newsList') ?>
</div>