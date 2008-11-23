<?php
// auto-generated by sfPropelCrud
// date: 2007/11/13 09:04:50
?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<!-- <h1><?php echo __('Wiki Result List'); ?></h1> -->

<?php //weflowTools::printR($ullwiki_pager); ?>
<?php //weflowTools::printR($sf_request); ?>

<?php
#     echo ull_form_tag(array('page' => '', 'search' => ''), #todo ull_reqpass...
     echo ull_form_tag('ullWiki/list',
                               array('class' => 'inline',
                                     'name' => 'ull_wiki_search_form'));
?>

<ul class='ull_action'>

    <li><?php echo ull_button_to(__('Create', null, 'common'), 'ullWiki/create'); ?></li>

    <?php echo $filter_form ?>
    <li><?php echo submit_tag('&gt;');?></li> 

</ul>

</form>


<br />

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager)
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

<?php

if ($docs): ?>
  <?php foreach ($docs as $doc): ?>
    <?php include_component('ullWiki', 'ullWikiHeader', array(
      'ullwiki' => $doc
//      ,'cursor' => $cursor
    )); ?>
    <?php //++$cursor;/?>
  <?php endforeach; ?>
<?php endif; ?>

<?php include_partial('ullTableTool/ullPagerBottom',
        array('pager' => $pager)
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