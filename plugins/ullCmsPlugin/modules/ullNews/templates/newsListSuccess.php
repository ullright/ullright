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
        <div class="ull_news_link"><?php echo link_to($newsEntry['link_name'], $newsEntry['link_url']) ?></div>
      </div>
    </div>
  <?php endforeach ?>
</div>