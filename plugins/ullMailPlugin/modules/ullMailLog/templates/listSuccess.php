<?php echo $breadcrumb_tree ?>

<h3>
  <?php echo $generator->getTableConfig()->getName() ?>
  "<?php echo $ull_newsletter_edition->subject ?>"
</h3>

<?php echo image_tag($chart, array('class' => 'ull_list_chart')) ?>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php echo $ull_filter ?>

<?php echo ull_form_tag(array('page' => '', 'filter' => array('search' => ''))) ?>

<ul class='list_action_buttons color_light_bg'>
  
  <li><?php echo ull_button_to(__('Create', null, 'common'), $create_base_uri); ?></li>
  
  <?php echo $filter_form ?>                
  
  <li>
    <?php echo submit_image_tag(ull_image_path('search', 16, 16, 'ullCore'),
              array('alt' => 'search_list', 'class' => 'image_align_middle_no_border')) ?>
  </li> 

</ul>
 
</form>

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager, 'paging' => $paging)
      ); ?>

<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator' => $generator,
      'order'     => $order,
  )); ?>
  
  <!-- data -->
  
  <tbody>
  <?php $odd = true; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    <?php $id_url_params = $generator->getIdentifierUrlParams($row); ?>
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
      <td class='no_wrap'>          
        <?php echo ull_link_to(ull_image_tag('edit'), ullCoreTools::appendParamsToUri($edit_base_uri, $id_url_params)); ?>
        <?php if ($generator->getAllowDelete()): ?>
          <?php echo ull_link_to(ull_image_tag('delete'), ullCoreTools::appendParamsToUri($delete_base_uri, $id_url_params), 
                'confirm=' . __('Are you sure?', null, 'common')); ?>
        <?php endif ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
  
<?php endif ?>

<?php include_partial('ullTableTool/ullPagerBottom', array('pager' => $pager)); ?> 

<?php use_javascripts_for_form($filter_form) ?>
<?php use_stylesheets_for_form($filter_form) ?>
<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>