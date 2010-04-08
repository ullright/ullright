<link rel="alternate" type="application/atom+xml" title="Latest Jobs"
  href="<?php echo url_for('/ullNews/newsListFeed', true) ?>" />

<h3>
  <?php echo __('News', null, 'common')?>
</h3>
<div class="ull_news">
    <div class="ull_news_entry">
      <div class="ull_news_image">
        image
      </div>
      <div class="ull_news_content">
        <div class="ull_news_date"><?php echo $doc['activation_date'] ?></div>
        <div class="ull_news_title"><?php echo $doc['title'] ?></div>
        <div class="ull_news_abstract"><?php echo $doc['abstract'] ?></div>
        <div class="ull_news_link"><?php echo link_to($doc['link_name'], $doc['link_url']) ?></div>
      </div>
    </div>



</div>