<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>
<?php $generator = $sf_data->getRaw('generator') ?>

<?php if ($filter_form->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $filter_form->renderGlobalErrors() ?>
  <?php foreach($filter_form as $widget){ echo $widget->renderLabel(); echo $widget->renderError(); } ?>
  </div>  
  <br /><br />
<?php endif; ?>



<?php
  echo ull_form_tag('ullVentory/list', array(
      'class' => 'inline',
      'name' => 'ull_ventory_search_form')
  );
?>

<ul class='list_action_buttons color_light_bg'>
    <li><?php echo ull_button_to(__('Enlist new item'), 'ullVentory/create' . ($entity ? '?entity=' . $entity->username : '')); ?></li>

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

<h3><?php if ($entity) { echo __('Items of') . ' ' . $entity; } ?></h3>

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager)
      ); ?>

<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator' => $generator,
      'order'     => $order,
      'order_dir' => $order_dir,
  )); ?>
  
  <!-- data -->
  
  <tbody>
  <?php $odd = false; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    <?php $identifier = $generator->getIdentifierUrlParams($row) ?>
    <?php //$form['subject']->getWidget()->setAttribute('href', 
      //ull_url_for(array_merge($generator->getIdentifierUrlParamsAsArray($row), array('action' => 'show')))) ?>
      <?php
        if ($odd) {
          $odd_style = ' class=\'odd\'';
          $odd = false;
        } else {
          $odd_style = '';
          $odd = true;
        }
      ?>
    <tr <?php echo $odd_style ?>>
      <td class='no_wrap'>          
        <?php
            echo ull_link_to(ull_image_tag('edit'), url_for('ull_ventory_edit', $form->getObject()));
            echo ull_link_to(ull_image_tag('delete'), 'ullVentory/delete?' . $identifier,
                'confirm='.__('Are you sure?', null, 'common')); 
        ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
<?php endif ?>