<div class="ull_news">
  <?php foreach ($newsEntries as $newsEntry): ?>
    <div class="ull_news_entry">
      <div class="ull_news_image">
        <?php echo "test-image" ?>
      </div>
      <div class="ull_news_content">
        <div class="ull_news_date"><?php echo $newsEntry['activation_date'] ?></div>
        <div class="ull_news_title"><?php echo $newsEntry['title'] ?></div>
        <div class="ull_news_abstract"><?php echo $newsEntry['abstract'] ?></div>
        <?php if ($newsEntry['link_name'] && $newsEntry['link_url']): ?>
        	<div class="ull_news_link"><?php echo link_to($newsEntry['link_name'], $newsEntry['link_url']) ?></div>
        <?php endif ?>
      </div>
    </div>
  <?php endforeach ?>
</div>