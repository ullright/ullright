<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>
<?php $generator = $sf_data->getRaw('generator') ?>
<?php $order = $sf_data->getRaw('order'); ?>

<?php if ($filter_form->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $filter_form->renderGlobalErrors() ?>
  <?php foreach($filter_form as $widget){ echo $widget->renderLabel(); echo $widget->renderError(); } ?>
  </div>  
  <br /><br />
<?php endif; ?>

<?php echo $ull_filter->getHtml(ESC_RAW) ?>
<?php echo ull_form_tag(array('page' => '', 'filter' => array('ull_entity_id' => ''))) ?>

<?php
  echo ull_form_tag('ullVentory/list', array(
      'class' => 'inline',
      'name' => 'ull_ventory_search_form')
  );
?>

<ul class='list_action_buttons color_light_bg'>
    <li><?php echo ull_button_to(__('Enlist new item', null, 'ullVentoryMessages'), 'ullVentory/create' . ($entity ? '?entity=' . $entity->username : '')); ?></li>
    
    <?php 
    if ($displayMassChangeOwnerButton && ($entity !== null))
    {
      echo '<li>' . ull_button_to(__('Change owner', null, 'ullVentoryMessages'), 'ullVentory/massChangeOwner'
        . '?oldEntityId=' . $entity->id)
        . '</li>';
    }
    ?>
    <li>
     <?php echo $filter_form['search']->renderLabel() ?>    
     <?php echo $filter_form['search']->render() ?>
    </li>

    <li>
     <?php echo $filter_form['ull_entity_id']->renderLabel() ?>    
     <?php echo $filter_form['ull_entity_id']->render() ?>
     <?php echo submit_image_tag(ull_image_path('search'),
              array('alt' => 'search_list', 'class' => 'image_align_middle_no_border')) ?>
    </li>      
</ul>

</form>

<h3><?php if ($entity) { echo __('Items of', null, 'ullVentoryMessages') . ' ' . $entity; } ?></h3>

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager)
      ); ?>

<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator' => $generator,
      'order'     => $order,
//      'order_dir' => $order_dir,
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