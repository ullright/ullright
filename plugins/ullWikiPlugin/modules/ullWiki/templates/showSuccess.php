<?php
// auto-generated by sfPropelCrud
// date: 2007/11/13 09:04:50
?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<!--<h1><?php echo __('Wiki Show'); ?></h1>-->

<?php
//if ($previous_cursor or $next_cursor) {
//  echo link_to_if(
//    $previous_cursor,
//    'previous',
//    'ullWiki/show?cursor=' . $previous_cursor);
//  echo ' - ';
//  echo link_to_if(
//    $next_cursor,
//    'next',
//    'ullWiki/show?cursor=' . $next_cursor);
//}
?>


<?php include_component(
        'ullWiki', 
        'ullWikiHeaderShow', 
        array('ullwiki' => $ullwiki, 'cursor' => 0)
      ); ?>

<?php //$ullwiki->setCulture(''); ?>

<?php
  if (isset($return_url)) {
    echo button_to(__('Link to this document', null, 'common'), $return_url);
    echo '<br /><br />';    
  }

?>

<div class='ullwiki_body'>
  <?php
  
  $body = $sf_data->getRaw('ullwiki')->getBody();
  
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

  
  $body = preg_replace_callback(
            '#<pre>.*?</pre>#is'
            , 'u_func'
            , $body);

  //auto "link" links
  $body = ullCoreTools::makelinks($body);

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

<?php include_partial(
        'ullWikiFooterShow', 
        array('ullwiki' => $ullwiki)
      ); ?>
