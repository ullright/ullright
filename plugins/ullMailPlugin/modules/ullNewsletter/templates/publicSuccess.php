<h1><?php echo __('Newsletter', null, 'ullMailMessages')?></h1>

<div id="ull_newsletter_subscription">

  <h2><?php echo __('Subscription', null, 'ullMailMessages')?></h2>

  <?php include_partial('ullTableTool/globalError', array('form' => $user_generator->getForm())) ?>
  
  <?php echo form_tag('ullNewsletter/public', array('multipart' => 'true', 'id' => 'ull_tabletool_form')) ?>
  
  <div class="edit_container">
  
    <?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>
    
    <?php include_partial('ullTableTool/editTable', array('generator' => $user_generator)) ?>
    
    <div class='edit_action_buttons color_light_bg'>
      <h3><?php echo __('Actions', null, 'common')?></h3>
      
      <div class='edit_action_buttons_left'>
        <ul>
        
            <li>
              <?php             
                echo ull_submit_tag(
                  __('Subscribe', null, 'ullMailMessages'),
                  array('name' => 'submit|action_slug=subscribe')
                );  
              ?>
            </li>
            
        </ul>
      </div>
    
    <!-- end of edit action buttons -->
    </div> 
  
  <!-- end of edit_container -->  
  </div>
  </form>
  
<!-- end of ull_newsletter_subscription -->  
</div>   

<div id="ull_newsletter_archive">

  <h2><?php echo __('Archive', null, 'ullMailMessages')?></h2>
  
  <?php foreach ($mailing_lists as $mailing_list): ?>
    <?php $editions = UllNewsletterEditionTable::findByMailingListIdNewestFirst($mailing_list->id) ?>
    <?php if (count($editions)): ?>
      <div class="ull_newsletter_archive_mailing_list">
        <h3><?php echo $mailing_list->name ?></h3>
        <ul>
        <?php foreach ($editions as $edition): ?>
          <li>
            <?php echo ull_format_date($edition->submitted_at)?> - 
            <?php echo $edition->subject?>
          </li>
        <?php endforeach ?>
        
        </ul>
      </div>
    <?php endif ?>
  <?php endforeach ?>
  

<!-- end of ull_newsletter_archive -->  
</div>     


<?php echo ull_js_observer("ull_tabletool_form") ?>  
<?php echo hide_advanced_form_fields() ?>

<?php use_javascripts_for_form($user_generator->getForm()) ?>
<?php use_stylesheets_for_form($user_generator->getForm()) ?>