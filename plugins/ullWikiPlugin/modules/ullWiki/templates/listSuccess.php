<?php
// auto-generated by sfPropelCrud
// date: 2007/11/13 09:04:50
?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<!-- <h1><?php echo __('Wiki Result List'); ?></h1> -->

<?php //weflowTools::printR($ullwiki_pager); ?>
<?php //weflowTools::printR($sf_request); ?>

<!-- Action row -->

<div class='action_buttons'>
  <div class='action_buttons_left'>  

    <?php
      // == Create link
      $create_link = 
        button_to(__('Create', null, 'common'), 'ullWiki/create') . ' &nbsp ';
      echo $create_link;
  
      // == search field
      //echo __('Search', null, 'common') . ': ';
      echo ull_reqpass_form_tag(array('page' => '', 'search' => ''),
                                array('class' => 'inline',
                                      'name' => 'ull_wiki_search_form'));
      
      echo $form['search']->render();

      echo ull_button_to_function(__('Search', null, 'common'), 'document.ull_wiki_search_form.submit();');
      
      echo '</form>';
      echo ' &nbsp ';
  
    ?>
  </div>
  <div class='clear'></div>
</div>
<br />

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $ullwiki_pager)
      ); ?>  


<br />
<?php echo __('Sort by', null, 'common'); ?>: 
<?php echo link_to(__('Subject', null, 'common'), 'ullWiki/list?sort=subject') ?>
 -
<?php echo link_to(__('DocId'), 'ullWiki/list?sort=docid') ?>
 -  
<?php echo link_to(__('Date ascending', null, 'common'), 'ullWiki/list?sort=updated_at') ?>
 -  
<?php echo link_to(__('Date descending', null, 'common'), 'ullWiki/list') ?>

<br />
<br />
 

<?php foreach ($ullwiki_pager->getResults() as $ullwiki): ?>
    <?php include_component('ullWiki', 'ullWikiHeader', array(
      'ullwiki' => $ullwiki
//      ,'cursor' => $cursor
    )); ?>
  <?php //++$cursor;/?>
<?php endforeach; ?>


<?php include_partial('ullTableTool/ullPagerBottom',
        array('pager' => $ullwiki_pager)
      ); ?> 

<!-- Action row -->
<br />
<div class='action_buttons'>
  <div class='action_buttons_left'>  
    <?php
      // == Create link
      $create_link = 
        button_to(__('Create', null, 'common'), 'ullWiki/create') . ' &nbsp ';
      echo $create_link;
    ?>
  </div>
  <div class='clear'></div>
</div>
<br />