<?php
/**
 * ullwiki header partial
 *
 * expexts a ullwiki object and prints the header
 * @package    ull_at
 * @subpackage ullwiki
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>
<div class='ullwiki_header_show'>

  <?php //$doc->setCulture(''); ?>
  <?php //$doc->setCulture(substr($sf_user->getCulture(), 0, 2)); ?>

  <div class='ullwiki_headfoot_float_right'>
    <?php include_component(
            'ullWiki',
            'ullWikiHeadFootActionIcons', 
            array(
              'doc'     => $doc
            )
          ); ?>
  </div>
 
  <div class='ull_wiki_header_title'>
    <h1>
      <?php //echo link_to($ullwiki->getSubject(), 'ullWiki/show?id='.$ullwiki->getID()); ?>
      <?php //echo link_to($ullwiki->getSubject(), 'ullWiki/show?id='.$ullwiki->getID().'&cursor='.$cursor); ?>
      <?php //echo link_to($doc->getSubject(), $sf_data->getRaw('subject_link')); ?>
      <?php echo $doc->getSubject(); ?>
    
    </h1>
    <!--  Tag1, Tag2, Tag3 -->
  </div>

  
  <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->


</div> <!-- end of ullwiki_header-->

<?php 
//$c = new sfCultureInfo('de');
//weflowTools::printR($c);
?>