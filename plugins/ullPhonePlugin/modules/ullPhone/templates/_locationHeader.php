<?php
  /**
   * This partial renders a table row containing information
   * about an UllLocation.
   * 
   * Expected arguments:
   *  location - an UllLocation in array form, or null
   *  colspanNumber - the number of columns the header should occupy
   */
?>
  
<tr>
  <td class="color_light_bg" colspan="<?php echo $colspanNumber ?>">
    
    <div class="float_left location_header">
    <?php
      if ($location === null)
      {
        echo '<b>' . __('No location specified', null, 'ullPhoneMessages') . '</b>';
      }
      else
      {
        echo '<b>' . $location['name'];
        if (!empty($location['short_name']))
        {
          echo ' (' . $location['short_name'] . ')';
        }
        
        //display a link to google maps if we have
        //a valid (partial) address
        if (!empty($location['city']) || (!empty($location['street']) && !empty($location['post_code'])))
        {
          $address = implode(' ', array($location['street'], $location['post_code'], $location['city']));
          echo
            '&nbsp;<small>' .
            link_to_google_maps(__('Map', null, 'ullPhoneMessages'), $address, array('target' => '_blank')) .
            '</small>';
        }
        
        echo '</b><br />' .
          __('Tel', null, 'common') . ': ' . $location['phone_base_no'] . ' ' . $location['phone_default_extension'] . '<br />' .
          __('Fax', null, 'common') . ': ' . $location['fax_base_no'] . ' ' . $location['fax_default_extension']
        ;
      }
    ?>
    </div>
    
    <div class="float_left">
    <?php
      if ($location !== null)
      {  
        echo
          '<br />' .
          $location['street'] . '<br />' .
          $location['post_code'] . ' ' . $location['city'] 
        ;
      }
    ?>
    </div>
  </td>
</tr>
