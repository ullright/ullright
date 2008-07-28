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

  <?php //$ullwiki->setCulture(''); ?>
  <?php //$ullwiki->setCulture(substr($sf_user->getCulture(), 0, 2)); ?>

  <div class='ullwiki_headfoot_float_right'>
    <?php include_component(
            'ullWiki',
            'ullWikiHeadFootActionIcons', 
            array(
              'ullwiki'     => $ullwiki
            )
          ); ?>
  </div>  

  <div class='ullwiki_header_headline'>
    <h3>
      <?php //echo link_to($ullwiki->getSubject(), 'ullWiki/show?id='.$ullwiki->getID()); ?>
      <?php //echo link_to($ullwiki->getSubject(), 'ullWiki/show?id='.$ullwiki->getID().'&cursor='.$cursor); ?>
      <?php echo link_to($ullwiki->getSubject(), $subject_link); ?>
    </h3>
    <!--  Tag1, Tag2, Tag3 -->
  </div>  

  
  <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->
  

</div> <!-- end of ullwiki_header-->

<?php 
//$c = new sfCultureInfo('de');
//weflowTools::printR($c);
?>