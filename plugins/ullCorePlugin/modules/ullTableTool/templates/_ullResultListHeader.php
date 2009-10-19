  <!-- header -->
  <thead>
  <tr>  
    <?php
      if (!(isset($add_icon_th) && $add_icon_th == false))
      {
        echo '<th class="color_dark_bg">&nbsp;</th>';
      }
    
      foreach ($generator->getAutoRenderedLabels() as $field_name => $label): ?>
      
      <th class="color_dark_bg">
        <?php 
        if ($order == $field_name) {
          $arrow  = ($order_dir == 'desc') ? ' <span class="order_arrow">↑</span>' : ' <span class="order_arrow">↓</span>';
          $dir    = ($order_dir == 'desc') ? 'asc' : 'desc';
        } else {
          $arrow = '';
          $dir = 'asc'; // always default to 'asc' order for a new column
        }
        
        echo ull_link_to(
          $label . $arrow
          , array(
              'order' => $field_name,
              'order_dir' => $dir,
            )
        );
      ?>      
      </th>
    <?php endforeach; ?>
  </tr>
  </thead>