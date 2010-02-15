  <tr>  
    <th class="color_dark_bg">&nbsp;</th>
    
    <?php foreach ($generator->getAutoRenderedLabels() as $field_name => $label): ?>
      <th class="color_dark_bg">
        <?php echo $label ?>
     </th>
    <?php endforeach; ?>
    
  </tr>
