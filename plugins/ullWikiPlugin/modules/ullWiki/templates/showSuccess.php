<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/src/shCore.js')?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushPhp.js')?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushBash.js')?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushCss.js')?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushSql.js')?>
<?php echo use_javascript('/ullCorePlugin/js/syntaxhighlighter/scripts/shBrushXml.js')?>
<?php echo use_stylesheet('/ullCorePlugin/js/syntaxhighlighter/styles/shCore.css')?>
<?php echo use_stylesheet('/ullCorePlugin/js/syntaxhighlighter/styles/shThemeDefault.css')?>


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
      ); ?>
</div>

<?php
  if (isset($return_url)) 
  {
    echo ull_button_to(__('Link to this document', null, 'common'), $sf_data->getRaw('return_url'));
    echo '<br /><br />';    
  }
?>

<div class='ull_wiki_main'>
  <?php
  
  $body = $sf_data->getRaw('doc')->body;
  
  /*
  // 's' extend the meaning of '.' beyond newlines
  preg_match_all("#<pre>(.*?)</pre>#is", $body, $matches); 
  
//  preg_match_all("#<code>[^<]+</code>#", $body, $matches);

//  ullCoreTools::printR($matches);

  foreach($matches[1] as $match) {
    
    $replace = html_entity_decode($match, null, 'UTF-8');
    $replace = str_replace('<br />', '', $replace);
    //    $replace = str_replace('<pre>', '<code>', $replace);
//    $replace = str_replace('</pre>', '</code>', $replace);

    
//    ullCoreTools::printR($replace);
    $highlighter = new dkGeshi($replace, 'php');
    $replace = $highlighter->parse_code();
    
//    $replace = str_replace('<pre>', '<code>', $replace);
//    $replace = str_replace('</pre>', '</code>', $replace);
    
    $body = str_replace($match, $replace, $body);
    
  }
*/

  
  
if (!function_exists('u_func')) {
  
  function u_func($matches) {
    
//    ullCoreTools::printR($matches);
    
    $match = $matches[0];
    
    
    
//    echo "<textarea cols=80 rows=10>$match</textarea>";
    
    $replace = html_entity_decode($match, ENT_QUOTES, 'UTF-8');
    
//    echo "<textarea cols=80 rows=10>$replace</textarea>";
    
//    $replace = html_entity_decode($replace, ENT_QUOTES, 'UTF-8');
//    
//    echo "<textarea cols=80 rows=10>$replace</textarea>";
    
    $replace = str_replace('<br />', '', $replace);
    $replace = str_replace("<pre>", '', $replace);
    $replace = str_replace('</pre>', '', $replace);

    
//    ullCoreTools::printR($replace);
    $highlighter = new dkGeshi($replace, 'php');
    $replace = $highlighter->parse_code();
    
    return $replace;
    
  }

}

  
  //$body = preg_replace_callback(
  //          '#<pre>.*?</pre>#is'
  //          , 'u_func'
  //          , $body);
  //INSTEAD:
  $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');  

  $body = auto_link_text($body, $link = 'all', array(
      'class'  => 'link_new_window',
      'target' => '_blank',
      'title'  => __('Link opens in a new window', null, 'common')
  ));
  

  echo $body;

//  ullCoreTools::printR($body);

//foreach($matches[1] as $match) {
//....
//}
//   $highlighter = new dkGeshi(, 'php');
//   echo $highlighter->parse_code();

//  echo $ullwiki->getBody(); 
  ?>
</div>

<div id="ull_wiki_footer">
<?php include_partial(
        'ullWikiFooterShow', 
        array('doc' => $doc, 'user_widget' => $user_widget)
      ); ?>
</div>

<?php echo javascript_tag('SyntaxHighlighter.all()') ?>