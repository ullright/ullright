<?php echo $breadcrumb_tree ?>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<h3>
  <?php echo __('Reporting', null, 'ullTimeMessages') ?>
</h3>

<?php include_partial('ullTableTool/globalError', array('form' => $filter_form)) ?>

<?php echo $ull_filter ?>

<?php 
  echo ull_form_tag(
    array('page' => '', 'filter' => array('ull_entity_id' => ''), 'single_redirect' => ''),
    array('class' => 'inline', 'name' => 'ull_time_project_search_form')    
  ) 
?>

<ul class='list_action_buttons color_light_bg'>
    <?php if ($report != 'by_user'): ?>
      <li>
        <?php echo $filter_form['search']->renderLabel() ?>    
        <?php echo $filter_form['search']->render() ?>
        <?php echo $filter_form['search']->renderError() ?>
      </li>
      <li>
        <?php echo $filter_form['ull_user_id']->renderLabel() ?>    
        <?php echo $filter_form['ull_user_id']->render() ?>
        <?php echo $filter_form['ull_user_id']->renderError() ?>
      </li>
    <?php endif ?>
    <?php if ($report != 'by_project'): ?>
      <li>
        <?php echo $filter_form['ull_project_id']->renderLabel() ?>    
        <?php echo $filter_form['ull_project_id']->render() ?>
        <?php echo $filter_form['ull_project_id']->renderError() ?>
     </li>   
   <?php endif ?>
   <li>
     <?php echo $filter_form['from_date']->renderLabel() ?>    
     <?php echo $filter_form['from_date']->render() ?>
     <?php echo $filter_form['from_date']->renderError() ?>
   </li>   
   <li>
     <?php echo $filter_form['to_date']->renderLabel() ?>    
     <?php echo $filter_form['to_date']->render() ?>
     <?php echo $filter_form['to_date']->renderError() ?>
     
     <?php echo submit_image_tag(ull_image_path('search'),
              array('alt' => 'search_list', 'class' => 'image_align_middle_no_border')) ?>
     
    </li>      
</ul>

<?php echo $filter_form->renderHiddenFields() ?>

</form>


<?php include_partial('ullTableTool/ullPagerTop', array(
  'pager' => $pager,
  'paging' => $paging,
  'disable_paging_hint' => true,
)) ?>

  

<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator'   => $generator,
      'order'       => $order,
      'add_icon_th' => $show_edit_action,
  )); ?>
  
  <!-- data -->
  <tbody>
  <?php $odd = true; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
  
    <?php $idAsArray = (array) $generator->getIdentifierUrlParamsAsArray($row); ?>
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
        <?php // special handling for comment -> decode for usable link ?>
      <?php if ($show_edit_action) : ?>
        <td class='no_wrap'>
          <?php
           $object = $generator->getSpecificRow($row);
           echo ull_link_to(ull_image_tag('edit'), array('action' => 'editProject',
            'id' => $idAsArray['id'], 'date' => $object['date'], 'username' => $user['username']));
          ?>
      <?php endif; ?>
      </td>
      <?php foreach ($generator->getAutoRenderedColumns() as $column_name => $column_config): ?>
        <?php if ($column_name == 'comment'): ?>
            <td><?php echo ullCoreTools::esc_decode($form[$column_name]) ?></td>
          <?php else: ?>
            <td><?php echo $form[$column_name] ?></td>
          <?php endif ?>
      <?php endforeach ?>
    </tr>
  <?php endforeach; ?>

  <?php if ($generator->getCalculateSums()): ?>
    <tr class="list_table_sum">
      <?php echo ($show_edit_action) ? '<td></td>' : ''; //fix double line display ?>
      <?php echo $generator->getSumForm() ?>
    </tr>
  <?php endif ?>
  
  </tbody>
  </table>
<?php endif ?>

<?php include_partial('ullTableTool/ullPagerBottom', array('pager' => $pager)); ?>

<?php use_javascripts_for_form($filter_form) ?>
<?php use_stylesheets_for_form($filter_form) ?>
<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>