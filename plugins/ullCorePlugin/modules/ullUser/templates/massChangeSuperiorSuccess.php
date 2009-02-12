<?php //echo $sf_data->getRaw('breadcrumb_tree')->getHtml() ?>
<br />
<h3><?php echo __('Superior mass change') ?></h3>

<form action="<?php echo url_for('ullUser/massChangeSuperior') ?>" method="POST">
  <table>
  <?php echo $form ?>
  <tr>
      <td colspan="2">
        <br />
        <?php echo submit_tag(__('Save superior change')) ?>
      </td>
    </tr>
  </table>
</form>