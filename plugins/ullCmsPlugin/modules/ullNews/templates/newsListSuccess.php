<link rel="alternate" type="application/atom+xml" title="Latest Jobs"
  href="<?php echo url_for('/ullNews/newsListFeed', true) ?>" />

<h3>
  <?php echo __('News', null, 'common')?>
</h3>
<div class="ull_news">
  <?php foreach ($newsEntries as $newsEntry): ?>
    <div class="ull_news_entry">
      <div class="ull_news_image">
        image
      </div>
      <div class="ull_news_content">
        <div class="ull_news_date"><?php echo $newsEntry['activation_date'] ?></div>
        <div class="ull_news_title"><?php echo $newsEntry['title'] ?></div>
        <div class="ull_news_abstract"><?php echo $newsEntry['abstract'] ?></div>
        <?php if($newsEntry['link_name'] && $newsEntry['link_url']): ?>
        	<div class="ull_news_link"><?php echo link_to($newsEntry['link_name'], $newsEntry['link_url']) ?></div>
        <?php endif ?>
      </div>
    </div>
  <?php endforeach ?>
  <ul></ul>
  <li class="feed">
  <a href="<?php echo url_for('/ullNews/newsListFeed') ?>">Full feed</a>
</li>
</ul>

</div>