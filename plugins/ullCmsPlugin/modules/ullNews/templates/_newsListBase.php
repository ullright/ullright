<?php 
  /* 
   * $image_location    configures the location of the image.
   *                    allowed options: 'left', 'bottom' (for mobile version) 
   */
?>
<div class="ull_news">

  <?php // create button ?>
  <?php if ($allow_edit): ?>
    <?php
      echo ull_button_to(
        __('Create news entry', null, 'ullNewsMessages'), 
        'ullNews/create', 
        array('id' => 'ull_news_create_news')
      );
    ?>              
  <?php endif ?>
       
  <?php // check if we have news entries at all ?>
  <?php if ($generator->getRow()->exists()): ?>
    <?php foreach ($generator->getForms() as $row => $form): ?>
      
      <?php $title                = $form['title']->render() ?>
      <?php $abstract             = $form['abstract']->render() ?>
      <?php $date                 = $form['activation_date']->render() ?>
      <?php $link                 = $form->getDefault('link_url') ?>     
      <?php $link_name            = $form['link_name']->render() ?>
      <?php $link_options         = array() ?>
      <?php $image                = $form['image_upload']->render() ?>
      <?php $image_link_options   = array() ?>

      <?php // pre-render image because we need it in 2 different locations ?>      
      <?php $image_html  = '<div class="ull_news_image">' ?>
      <?php if ($link && $image): ?>
        <?php $image_html .=  ull_link_to($image, $link, $image_link_options) ?>
      <?php else: ?>
        <?php $image_html .= $image ?>
      <?php endif ?>
      <?php $image_html .= '</div>' ?>
      
      <?php // handle external links ?>
      <?php if (substr($link, 0, 7) == 'http://'): ?>
        <?php $link_options = array('link_new_window' => true, 'link_external' => true) ?>
        <?php $image_link_options = array('target' => '_blank') ?>
      <?php endif ?>
      
      <div class="ull_news_entry">
      
        <?php // render image for normal version (=non mobile)?>
        <?php if ($image_location == 'left'): ?>
          <?php echo $image_html ?>
        <?php endif ?>
        
        <div class="ull_news_content">
        
          <!-- date -->
          <div class="ull_news_date">
            <?php echo $date ?>
          </div>
          
          <!-- title -->
          <h3 class="ull_news_title">
          
            <?php // edit icon ?>
            <?php if ($allow_edit): ?>
              <?php echo ull_link_to(
                ull_image_tag('edit'),
                array(
                  'module' => 'ullNews', 
                  'action' => 'edit', 
                  'id' => $form->getObject()->id
                )) ?>
            <?php endif ?>

            <?php if ($link): ?>
              <?php echo ull_link_to($title, $link, $link_options) ?>
            <?php else: ?>
              <?php echo $title ?>
            <?php endif ?>
            
          </h3>
          
          <!-- text -->
          <p class="ull_news_abstract">
            <?php echo $abstract ?>
          </p>
          
          <?php if ($link && $link_name): ?>
            <p class="ull_news_link">
              <?php echo ull_link_to($link_name, $link, $link_options) ?>
            </p>
          <?php endif ?>
          
        </div>
        <!-- end of ull_news_content -->
        
        <?php // render image for mobile version ?>
        <?php if ($image_location == 'bottom'): ?>
          <?php echo $image_html ?>
        <?php endif ?>        
        
      </div>
      <!-- end of ull_news_entry -->
      
    <?php endforeach ?>
  <?php endif // on of check if we have news entries at all?>
  
</div>
<!-- end of ull_news -->