<?xml version="1.0" encoding="utf-8"?>

<?php $uuid = sha1(sfConfig::get('app_ull_news_rss_title', 'ullright newsfeed') . url_for('@homepage', true))?>

<feed xmlns="http://www.w3.org/2005/Atom">
  <author>
    <name><?php echo sfConfig::get('app_ull_news_rss_author') ?></name>
  </author>
  <title><?php echo __(sfConfig::get('app_ull_news_rss_title', 'ullright newsfeed'), null, 'ullNewsMessages') ?></title>
 	<id>urn:uuid:<?php echo $uuid ?></id>
 	<logo><?php echo sfConfig::get('app_ull_news_rss_logo') ?></logo>
  <updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findLatestNews()->getDateTimeObject('activation_date')->format('U')) ?></updated>

  <?php foreach($newsEntries as $newsEntry):?>
  	<entry>
		<title><?php echo $newsEntry['title'] ?></title>
		<?php if ($newsEntry['link_url']): ?>
  		<link href="<?php echo $newsEntry['link_url'] ?>" title="<?php echo $newsEntry['link_name'] ?>"/>
  	<?php endif ?>
  	<id>urn:uuid:<?php echo sha1($newsEntry['slug'] . $newsEntry['created_at'] . $uuid) ?></id>
  	<updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findOneBySlug($newsEntry['slug'])->getDateTimeObject('activation_date')->format('U')) ?></updated>
    <summary><?php echo $newsEntry['abstract'] ?></summary>
  </entry>
  <?php endforeach ?>
</feed>

