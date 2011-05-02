<div class="ull_news">
  <?php if ($allow_edit): ?>
    <?php
      echo ull_button_to(
        __('Create news entry', null, 'ullNewsMessages'), 
        'ullNews/create', 
        array('id' => 'ull_news_create_news')
      );
    ?>              
  <?php endif ?>
  <?php if ($generator->getRow()->exists()): ?>
    <?php foreach ($generator->getForms() as $row => $form): ?>
     
      <?php $link_options = array();?>
      <?php $image_link_options = array();?>
      
      <?php if (substr($form->getDefault('link_url'), 0, 7) == 'http://'): ?>
        <?php $link_options = array('link_new_window' => true, 'link_external' => true) ?>
        <?php $image_link_options = array('target' => '_blank') ?>
      <?php endif ?>
      
      <div class="ull_news_entry">
        <div class="ull_news_image">
          <?php if ($form->getDefault('link_url') && $form['image_upload']->render()): ?>
            <?php echo ull_link_to($form['image_upload']->render(), 
              $form->getDefault('link_url'), $image_link_options) ?>
          <?php else: ?>
            <?php echo $form['image_upload']->render() ?>
          <?php endif ?>
        </div>
        <div class="ull_news_content">
          <div class="ull_news_date"><?php echo $form['activation_date']->render() ?></div>
          <?php
            echo '<h3 class="ull_news_title">';
            if ($allow_edit)
            {
              echo ull_link_to(ull_image_tag('edit'),
                array('module' => 'ullNews', 'action' => 'edit', 'id' => $form->getObject()->id));
            }
            if ($form->getDefault('link_url'))
            {
              echo ull_link_to(
                $form['title']->render(),
                $form->getDefault('link_url'), 
                $link_options
              );
            }
            else
            {
              echo $form['title']->render();
            }
            echo '</h3>';
          ?>
          
          <p class="ull_news_abstract"><?php echo $form['abstract']->render() ?></p>
          <?php if ($form['link_name']->render() && $form->getDefault('link_url')): ?>
            <p class="ull_news_link">
              <?php echo ull_link_to($form['link_name']->render(), 
               $form->getDefault('link_url'), $link_options) ?>
            </p>
          <?php endif ?>
        </div>
      </div>
    <?php endforeach ?>
  <?php endif ?>
</div>