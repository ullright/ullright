<?php echo $breadcrumb_tree ?>
<?php $generator = $sf_data->getRaw('generator') ?>

<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>

  <!-- data -->
  
  <tbody>
  <?php $odd = false; ?>
  <?php $old_assigned_to = false; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    
    <?php $assigned_to = strip_tags($form['assigned_to_ull_entity_id']->render()) ?>
  
    <?php if ($assigned_to != $old_assigned_to): ?>
      <tr class='list_table_group_header'>
        <td colspan="6">
          <h4><?php echo $assigned_to ?></h4>
        </td>
      </tr>
      
      <?php include_partial('ullTableTool/ullResultListHeaderNoOrder', array(
          'generator' => $generator,
      )); ?>   
      
      <?php $old_assigned_to = $assigned_to ?>
      <?php $odd = false; ?>
    <?php endif?>
  
  
    <?php
      $identifiers = (array) $generator->getIdentifierUrlParamsAsArray($row);
      $form['subject']->getWidget()->setAttribute('href', 
        ull_url_for(array_merge($identifiers, array('action' => 'edit'))));
    ?>
      <?php
        if ($odd) 
        {
          $odd_style = ' class="odd"';
          $odd = false;
        } 
        else 
        {
          $odd_style = '';
          $odd = true;
        }
        $identifier = $generator->getIdentifierUrlParams($row);
      ?>
    <tr <?php echo $odd_style ?>>
      <td class='no_wrap'>          
        <?php
            echo ull_link_to(ull_image_tag('edit'), 'ullFlow/edit?' . $identifier);
            echo ull_link_to(ull_image_tag('delete'), 'ullFlow/delete?' . $identifier,
                'confirm='.__('Are you sure?', null, 'common')); 
        ?>
      </td>
      <?php foreach ($generator->getAutoRenderedColumns() as $column_name => $columns_config): ?> 
        <td><?php echo $form[$column_name]->render() ?></td>          
      <?php endforeach ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
  
<?php endif ?>



