<?php //echo $sf_data->getRaw('breadcrumb_tree')->getHtml() ?>
<br />
<h3><?php echo __('Superior mass change') ?></h3>
<br />
<?php
if ($ok == 1)
{
  if ($cnt == 0)
  {
    echo __('There are no subordinated users for the given superior.') . '<br /><br />';
    echo ull_link_to(__('Return to previous page', null, 'common'), 'ullUser/massChangeSuperior');
  }
  else {
      echo format_number_choice('[1]The superior was successfully replaced for one user.' .
          '|(1,+Inf]The superior was successfully replaced for %1% users.',
          array('%1%' => $cnt), $cnt) . '<br /><br />';
      
      echo ull_link_to(__('Return to admin page', null, 'common'), 'ullAdmin/index');
  }
}
else {
  echo __('The replacing superior must be different from the old superior.') . '<br /><br />';
  echo ull_link_to(__('Return to previous page', null, 'common'), 'ullUser/massChangeSuperior');
}
?>
<br />

