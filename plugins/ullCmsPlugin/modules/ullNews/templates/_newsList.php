<div class="ull_news">
  <?php if ($generator->getRow()->exists()): ?>
    <?php foreach ($generator->getForms() as $row => $form): ?>
     
      <?php $link_options = array();?>
      <?php if (substr($form['link_url']->render(), 0, 7) == 'http://')
            {
              $link_options = array('link_new_window' => true, 'link_external' => true);
            } 
      ?>
      
      <div class="ull_news_entry">
        <div class="ull_news_image">
          <?php if ($form['link_url']->render() && $form['image_upload']->render()): ?>
            <?php echo ull_link_to($form['image_upload']->render(), 
              $form['link_url']->render(), $link_options) ?>
          <?php else: ?>
            <?php echo $form['image_upload']->render() ?>
          <?php endif ?>
        </div>
        <div class="ull_news_content">
          <div class="ull_news_date"><?php echo $form['activation_date']->render() ?></div>
          <h3 class="ull_news_title">
            <?php if ($allow_edit): ?>
              <?php
                echo ull_link_to(ull_image_tag('edit'),
                  array('module' => 'ullNews', 'action' => 'edit', 'id' => $form->getObject()->id));
              ?>              
            <?php endif ?>
            <?php echo $form['title']->render() ?>
          </h3>
          <p class="ull_news_abstract"><?php echo $form['abstract']->render() ?></p>
          <?php if ($form['link_name']->render() && $form['link_url']->render()): ?>
            <p class="ull_news_link">
              <?php echo ull_link_to($form['link_name']->render(), 
               $form['link_url']->render(), $link_options) ?>
            </p>
          <?php endif ?>
        </div>
      </div>
    <?php endforeach ?>
  <?php endif ?>
</div>