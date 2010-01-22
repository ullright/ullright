<?php //echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php include_partial('ullTableTool/globalError', array('form' => $filter_form)) ?>

<?php echo $ull_filter ?>

<?php 
  echo ull_form_tag(
    array('page' => '', 'filter' => array('ull_entity_id' => ''), 'single_redirect' => ''),
    array('class' => 'inline', 'name' => 'ull_time_project_search_form')    
  ) 
?>

<ul class='list_action_buttons color_light_bg'>
    <li>
     <?php echo $filter_form['ull_user_id']->renderLabel() ?>    
     <?php echo $filter_form['ull_user_id']->render() ?>
     <?php echo submit_image_tag(ull_image_path('search'),
              array('alt' => 'search_list', 'class' => 'image_align_middle_no_border')) ?>
     
    </li>      
</ul>

<?php echo $filter_form->renderHiddenFields() ?>

</form>


<?php include_partial('ullTableTool/ullPagerTop',
    array('pager' => $pager, 'paging' => $paging)) ?>


<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator'   => $generator,
      'order'       => $order,
      'add_icon_th' => false,
  )); ?>
  
  <!-- data -->
  <tbody>
  <?php $odd = true; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    <?php $idAsArray = (array) $generator->getIdentifierUrlParamsAsArray($row); ?>
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>

  <?php if ($generator->getCalculateSums()): ?>
    <tr class="list_table_sum">
      <?php echo $generator->getSumForm() ?>
    </tr>
  <?php endif ?>
  
  </tbody>
  </table>
<?php endif ?>

<?php include_partial('ullTableTool/ullPagerBottom', array('pager' => $pager)); ?>