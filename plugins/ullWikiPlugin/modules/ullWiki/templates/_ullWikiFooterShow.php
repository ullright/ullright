<?php
/**
 * ullwiki footer partial
 *
 * expexts a ullwiki object and prints the show footer
 * 
 * @package    ull_at
 * @subpackage ullwiki
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>
<div class='ullwiki_footer_show'>

  <div class='ullwiki_headfoot_float_right'>
    <?php include_component(
            'ullWiki',
            'ullWikiHeadFootActionIcons', 
            array(
              'doc'     => $doc
            )
          ); ?>
  </div>  

  <?php include_partial(
          'ullWikiHeadFootDocInfo',
          array(
              'doc'     => $doc
            )
          ) ?>
  
  <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->
  
</div> <!-- end of ullwiki_footer-->