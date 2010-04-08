<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <author>
    <name>Südwand Admin</name>
  </author>
  <title>Kletterzentrum - Südwand</title>
 	<id>urn:uuid:<?php echo sha1('kletterzentrum-suedwand.at') ?></id>
 	<logo>http://www.kletterzentrum-suedwand.at/images/layout/logo_120.png</logo>
  <updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findLatestNews()->getDateTimeObject('activation_date')->format('U')) ?></updated>

  <?php foreach($newsEntries as $newsEntry):?>
  	<entry>
		<title><?php echo $newsEntry['title'] ?></title>
  	<link href="http://ullright/ullNews/show/slug/<?php echo $newsEntry['slug'] ?>"/>
  	<id>urn:uuid:kletterzentrum-suedwand.at-<?php echo sha1($newsEntry['slug'] . $newsEntry['created_at']) ?></id>
  	<updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findOneBySlug($newsEntry['slug'])->getDateTimeObject('activation_date')->format('U')) ?></updated>
    <summary><?php echo $newsEntry['abstract'] ?></summary>
  </entry>
  <?php endforeach ?>
</feed>

