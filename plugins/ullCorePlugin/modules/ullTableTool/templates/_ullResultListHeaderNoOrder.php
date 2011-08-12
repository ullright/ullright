  <tr>  

    <?php $renderIconHeader = !(isset($add_icon_th) && $add_icon_th == false) ? true : false ?>
  
    <?php if ($renderIconHeader): ?>
      <th class="color_dark_bg">&nbsp;</th>
    <?php endif ?>
    
    <?php foreach ($generator->getAutoRenderedLabels() as $field_name => $label): ?>
      <th class="color_dark_bg <?php echo 'th_' . $field_name?>">
        <?php echo $label ?>
     </th>
    <?php endforeach; ?>
    
  </tr>
