<?php use_javascript('/ullVentoryPlugin/js/createSuccess.js') ?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php if ($form->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $form->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>


<?php echo form_tag(url_for('ullVentory/create?entity=' . $entity->username), array('id' => 'ull_ventory_form')); ?>

<div class="edit_container">

<table class="edit_table" id="ull_ventory_item">
<tbody>
  <tr>
    <td class="label_column"><?php echo $form['type']->renderLabel() ?></td>
    <td>
      <?php echo $form['type']->render() ?>
      <?php echo submit_tag(__('Apply', null, 'common'), array('name' => false, 'id' => 'type_submit')) ?>
    </td>
    <td class="form_error"><?php echo $form['type']->renderError() ?></td>
  </tr>
</tbody>
</table>

<?php echo $form->renderHiddenFields() ?>

</div>

</form>

