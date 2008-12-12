<?php
/**
 * ull_flow header partial
 *
 * expexts an ull_flow_instance object and prints the header
 * @package    ull_at
 * @subpackage ull_flow
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
?>
<div class='ull_flow_header'>

  <div class='ull_flow_headfoot_float_right'>
    <?php 
          include_component(
            'ullFlow',
            'ullFlowHeadFootActionIcons', 
            array(
              'ull_flow_doc'     => $ull_flow_doc
            )
          ); 
    ?>
  </div>  
  
  <div class='ull_flow_header_headline'>
    <h3 class='ull_flow_header_subject'>
      <?php echo link_to($ull_flow_doc->getTitle(), 'ullFlow/edit?doc=' . $ull_flow_doc->getId()); ?>
    </h3> &nbsp; <span class='ull_flow_header_subtitle'>(DocID: <?php echo $ull_flow_doc->getId(); ?>)</span>
    
    <?php
      if (!$app_slug) { 
        echo link_to(
          ullCoreTools::getI18nField($ull_flow_doc->getUllFlowApp(), 'caption')
          , 'ullFlow/list?app=' . $ull_flow_doc->getUllFlowApp()->getSlug()
        );
      } 
    ?>
    <!--  Tag1, Tag2, Tag3 -->
  </div> 
  
  <!-- <div class='clear'></div> -->

  <?php include_partial(
          'ullFlowHeadFootInfo',
          array(
              'ull_flow_doc'     => $ull_flow_doc
            )
          ) ?>
  
  <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->
  

</div> <!-- end of ull_flow_header-->