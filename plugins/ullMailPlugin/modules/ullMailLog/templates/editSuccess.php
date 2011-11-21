<?php /* Set defaults */ ?>
<?php if (!isset($form_html_id)) { $form_html_id = 'ull_tabletool_form'; } ?>
<?php if (!isset($is_ajax)) { $is_ajax = false; } ?>

<?php echo $breadcrumb_tree ?>

<?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>

<?php echo form_tag($form_uri, array('multipart' => 'true', 'id' => $form_html_id)) ?>

<div class="edit_container">

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php include_partial('ullTableTool/editTable', array('generator' => $generator)) ?>

</div>

<?php echo hide_advanced_form_fields() ?>

<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>