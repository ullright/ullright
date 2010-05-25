<?php echo $breadcrumb_tree ?>
<?php $generator = $sf_data->getRaw('generator') ?>
<?php $order = $sf_data->getRaw('order'); ?>

<?php include_partial('ullTableTool/globalError', array('form' => $filter_form)) ?>

<?php echo $ull_filter ?>

<?php 
  echo ull_form_tag(
    array('page' => '', 'filter' => array('ull_entity_id' => ''), 'single_redirect' => ''),
    array('class' => 'inline', 'name' => 'ull_ventory_search_form')    
  ) 
?>

<ul class='list_action_buttons color_light_bg'>
    <li><?php echo ull_button_to(__('Enlist new item', null, 'ullVentoryMessages'), 'ullVentory/create' . ($entity ? '?entity=' . $entity->username : '')); ?></li>
    
    <?php 
    if ($display_mass_change_owner_button && ($entity !== null))
    {
      echo '<li>' . ull_button_to(__('Change owner', null, 'ullVentoryMessages'), 'ullVentory/massChangeOwner'
        . '?oldEntityId=' . $entity->id)
        . '</li>';
    }
    ?>
    <li>
     <?php echo $filter_form['search']->renderLabel() ?>    
     <?php echo $filter_form['search']->render() ?>
     <?php // render no value echo $filter_form['search']->render(array('value' => '')) ?>
    </li>

    <li>
     <?php echo $filter_form['ull_entity_id']->renderLabel() ?>    
     <?php echo $filter_form['ull_entity_id']->render() ?>
     <?php echo submit_image_tag(ull_image_path('search'),
              array('alt' => 'search_list', 'class' => 'image_align_middle_no_border')) ?>
     
    </li>      
</ul>

<?php echo $filter_form->renderHiddenFields() ?>

</form>

<h3><?php if ($entity) { echo __('Items of', null, 'ullVentoryMessages') . ' ' . $entity; } ?></h3>

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager, 'paging' => $paging)
      ); ?>

<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator' => $generator,
      'order'     => $order,
  )); ?>
  
  <!-- data -->
  
  <tbody>
  <?php $odd = true; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    <?php /* $form['inventory_number']->getWidget()->setAttribute('href', 
      url_for('ull_ventory_edit', $form->getObject())) */ ?>
    <?php /*TODO: use injectIdentifier? or array() value? */ $form['artificial_toggle_inventory_taking']->getWidget()->setAttribute('href', 
      url_for('ull_ventory_toggle_inventory_taking', $form->getObject())) ?>      
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
      <td class='no_wrap'>          
        <?php
            echo ull_link_to(ull_image_tag('edit'), url_for('ull_ventory_edit', $form->getObject()));
         //   echo ull_link_to(ull_image_tag('delete'), 'ullVentory/delete?' . $identifier,
           //     'confirm='.__('Are you sure?', null, 'common')); 
        ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
      
<?php endif ?>

<?php include_partial('ullTableTool/ullPagerBottom', array('pager' => $pager)); ?> 

<?php use_javascripts_for_form($filter_form) ?>
<?php use_stylesheets_for_form($filter_form) ?>
<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>