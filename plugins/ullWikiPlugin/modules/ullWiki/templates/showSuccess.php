<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/src/shCore.js') ?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushPhp.js') ?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushBash.js') ?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushCss.js') ?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushSql.js') ?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushXml.js') ?>
<?php echo use_stylesheet('/ullCorePlugin/js/syntaxhighlighter/styles/shCore.css') ?>
<?php echo use_stylesheet('/ullCorePlugin/js/syntaxhighlighter/styles/shThemeDefault.css') ?>


<?php echo $breadcrumb_tree ?>

<?php $user_widget = $sf_data->getRaw('user_widget') ?>

<?php if ($has_no_write_access): ?>
  <br />
  <div class='form_error'><?php echo __('Sorry, no permission to edit this page. Displaying read-only version.', null, 'common')?></div>
  <br /><br />
<?php endif ?>  

<div id="ull_wiki_header">
  <?php include_component(
    'ullWiki', 
    'ullWikiHeaderShow', 
    array('doc' => $doc, 'cursor' => 0)
  ) ?>
</div>

<?php if (isset($return_url)): ?>  
  <?php echo ull_button_to(__('Link to this document', null, 'common'), 
    $sf_data->getRaw('return_url')) ?>
  <br /><br />    
<?php endif ?>

<div class='ull_wiki_main'>
  <?php
    $body = $sf_data->getRaw('doc')->body;
  
    $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');  
  
    $body = auto_link_text($body, $link = 'all', array(
        'class'  => 'link_new_window',
        'target' => '_blank',
        'title'  => __('Link opens in a new window', null, 'common')
    ));
    
  
    echo $body;
  ?>
</div>

<div id="ull_wiki_footer">
<?php include_partial(
        'ullWikiFooterShow', 
        array('doc' => $doc, 'user_widget' => $user_widget)
      ); ?>
</div>

<?php echo javascript_tag('SyntaxHighlighter.all()') ?>