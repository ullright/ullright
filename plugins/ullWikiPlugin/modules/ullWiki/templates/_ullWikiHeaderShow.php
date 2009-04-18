<?php
/**
 * ullWiki header partial
 *
 * expects an UllWiki object and outputs the header
 * 
 * @package    ullright
 * @subpackage ullWiki
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>

<div class='ull_wiki_header_show'>

  <div class='ull_wiki_headfoot_float_right'>
    <?php include_component(
            'ullWiki',
            'ullWikiHeadFootActionIcons', 
            array(
              'doc'     => $doc
            )
          ); ?>
  </div>
 
  <div class='ull_wiki_header_title'>
    <h1><?php echo $doc->getSubject() ?></h1>
  </div>

  <div class='clear_right'></div> <!-- to force the parent-box to enclose the floating divs -->

</div> <!-- end of ull_wiki_header-->
