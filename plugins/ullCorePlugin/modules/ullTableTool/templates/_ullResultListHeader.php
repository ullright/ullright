  <!-- header -->
  
  <?php
    $renderIconHeader = !(isset($add_icon_th) && $add_icon_th == false) ? true : false;
    //$columnCount = count($generator->getAutoRenderedLabels());
    if ($renderIconHeader)
    {
      echo '<col class="col_icon_header" />';
    }
    
    foreach ($generator->getAutoRenderedLabels() as $field_name => $label)
    {
      echo '<col class="col_' . $field_name . '_header" />';
    }
  ?>
  
  <thead>
  <tr>  
    <?php
      if ($renderIconHeader)
      {
        echo '<th class="color_dark_bg">&nbsp;</th>';
      }
    
      $order = $sf_data->getRaw('order');
      $order_array = ullGeneratorTools::arrayizeOrderBy($order);
      $order_first_column = reset($order_array);
        
      $unsortable_columns = $generator->getUnsortableColumns();
      
      foreach ($generator->getAutoRenderedLabelsWithDefaultOrder() as $field_name => $field): ?>
      
      <th class="color_dark_bg">
      <?php
        if (isset($unsortable_columns[$field_name]))
        {
          echo $field['label'];
        }
        else
        {
          if ($order_first_column['column'] == $field_name) 
          {
            $arrow  = ($order_first_column['direction'] == 'desc') ? ' <span class="order_arrow">↑</span>' : ' <span class="order_arrow">↓</span>';
            $dir    = ($order_first_column['direction'] == 'desc') ? 'asc' : 'desc';
          } 
          else 
          {
            $arrow = '';
            $dir = $field['defaultOrderDirection']; //the column config provides the default order direction
          }
          
          echo ull_link_to(
            $field['label'] . $arrow
            , array(
                'order' => UllGeneratorTools::convertOrderByFromQueryToUri($field_name . ' ' . $dir)
              )
          );
        }
      ?>      
      </th>
    <?php endforeach; ?>
  </tr>
  </thead>