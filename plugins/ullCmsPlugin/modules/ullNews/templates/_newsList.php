<div class="ull_news">
  <?php foreach ($generator->getForms() as $row => $form): ?>
    <div class="ull_news_entry">
      <div class="ull_news_image">
        <?php echo $form['image_upload']->render() ?>
      </div>
      <div class="ull_news_content">
        <div class="ull_news_date"><?php echo $form['activation_date']->render() ?></div>
        <div class="ull_news_title"><?php echo $form['title']->render() ?></div>
        <div class="ull_news_abstract"><?php echo $form['abstract']->render() ?></div>
        <?php if ($form['link_name']->render() && $form['link_url']->render()): ?>
        	<div class="ull_news_link">
        	 <?php echo link_to($form['link_name']->render(), 
        	   $form['link_url']->render()) ?>
        	</div>
        <?php endif ?>
      </div>
    </div>
  <?php endforeach ?>
</div>