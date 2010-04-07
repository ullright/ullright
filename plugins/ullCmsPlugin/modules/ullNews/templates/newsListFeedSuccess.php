<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <author>
    <name>Südwand Admin</name>
  </author>
  <title>Südwand - Kletterzentrum</title>
 	<id>urn:uuid:<?php echo sha1('suedwand.at') ?></id>
  <updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findLatestNews()->getDateTimeObject('activation_date')->format('U')) ?></updated>

  <?php foreach($newsEntries as $newsEntry):?>
  	<entry>
			<title><?php echo $newsEntry['title'] ?></title>
  	<link href="http://suedwand.at"/>
  	<id>urn:uuid:<?php echo sha1($newsEntry['slug'] . $newsEntry['activation_date']) ?></id>
  	<updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', Doctrine::getTable('UllNews')->findOneBySlug($newsEntry['slug'])->getDateTimeObject('activation_date')->format('U')) ?></updated>
    <summary><?php echo $newsEntry['abstract'] ?></summary>
    <content>Volltext des Weblog-Eintrags</content>
  </entry>
  <?php endforeach ?>
</feed>

