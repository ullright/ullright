<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <author>
    <name>Example Author</name>
  </author>
  <title>Example</title>
 	<id>urn:uuid:<?php echo sha1('example.at') ?></id>
 	<logo>http://upload.wikimedia.org/wikipedia/commons/4/43/Feed-icon.svg</logo>
  <updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findLatestNews()->getDateTimeObject('activation_date')->format('U')) ?></updated>

  <?php foreach($newsEntries as $newsEntry):?>
  	<entry>
		<title><?php echo $newsEntry['title'] ?></title>
		<?php if ($newsEntry['link_url']): ?>
  		<link href="<?php echo $newsEntry['link_url'] ?>" title="<?php echo $newsEntry['link_name'] ?>"/>
  	<?php endif ?>
  	<id>urn:uuid:example.at-<?php echo sha1($newsEntry['slug'] . $newsEntry['created_at']) ?></id>
  	<updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findOneBySlug($newsEntry['slug'])->getDateTimeObject('activation_date')->format('U')) ?></updated>
    <summary><?php echo $newsEntry['abstract'] ?></summary>
  </entry>
  <?php endforeach ?>
</feed>

