<?php echo $breadcrumb_tree ?>
<h1><?php echo $course['name']?></h1>

<?php include_partial('ullCourse/courseSummary', array('course' => $course)) ?>

<h2><?php echo __('Participants', null, 'ullCourseMessages') ?></h2>

<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeaderNoOrder', array(
      'generator' => $generator,
      'add_icon_th' => false
  )); ?>
  
  <!-- data -->
  
  <tbody>
  <?php $odd = true; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    <?php $id_url_params = $generator->getIdentifierUrlParams($row); ?>
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
      <?php /* 
      <td class='no_wrap'>          
        <?php echo ull_link_to(ull_image_tag('edit'), ullCoreTools::appendParamsToUri($edit_base_uri, $id_url_params)); ?>
        <?php if ($generator->getAllowDelete()): ?>
          <?php echo ull_link_to(ull_image_tag('delete'), ullCoreTools::appendParamsToUri($delete_base_uri, $id_url_params), 
                'confirm=' . __('Are you sure?', null, 'common')); ?>
        <?php endif ?>
      </td>
      */ ?>
      <?php echo $form ?>
      
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
  
<?php endif ?>