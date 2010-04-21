<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>

<?php $uuid = sha1(sfConfig::get('app_ull_news_rss_title', 'ullright newsfeed') . url_for('@homepage', true))?>

<feed xmlns="http://www.w3.org/2005/Atom">
  <author>
    <name><?php echo sfConfig::get('app_ull_news_rss_author') ?></name>
  </author>
  <title><?php echo __(sfConfig::get('app_ull_news_rss_title', 'ullright newsfeed')) ?></title>
 	<id>urn:uuid:<?php echo $uuid ?></id>
 	<logo><?php echo sfConfig::get('app_ull_news_rss_logo') ?></logo>
  <updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findLatestNews()->getDateTimeObject('activation_date')->format('U')) ?></updated>

  <?php foreach($newsEntries as $newsEntry):?>
  	<entry>
		<title><?php echo $newsEntry['title'] ?></title>
		<link href="<?php echo url_for('@homepage', true) ?>"/>
  	<id>urn:uuid:<?php echo sha1($newsEntry['slug'] . $newsEntry['created_at'] . $uuid) ?></id>
  	<updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findOneBySlug($newsEntry['slug'])->getDateTimeObject('activation_date')->format('U')) ?></updated>
    <summary type="html"><![CDATA[
      <p><?php echo nl2br($newsEntry['abstract']) ?></p>
      <?php if ($newsEntry['link_name'] && $newsEntry['link_url']): ?>
        <p><?php echo link_to($newsEntry['link_name'], $newsEntry['link_url'], array('absolute' => true)) ?></p> 
    <?php endif ?> ]]>
    </summary>
  </entry>
  <?php endforeach ?>
</feed>

