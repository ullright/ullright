<div class="cms_content">

<h1 class="ull_cms_content_heading"><?php echo $generator->getTableConfig()->getName() ?></h1>
<p><?php echo $generator->getTableConfig()->getDescription() ?></p>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php include_partial('ullCourse/offeringQuickFilter') ?>



<?php //echo $ull_filter ?>

<?php  /* ?>
<?php echo ull_form_tag(array('page' => '', 'filter' => array('search' => ''))) ?>

<ul class='list_action_buttons color_light_bg'>
  
  <?php <li><?php echo ull_button_to(__('Create', null, 'common'), $create_base_uri); ?></li> ?>
  
  <?php echo $filter_form ?>                
  
  <li>
    <?php echo submit_image_tag(ull_image_path('search', 16, 16, 'ullCore'),
              array('alt' => 'search_list', 'class' => 'image_align_middle_no_border')) ?>
  </li> 

</ul>
 
</form>
*/ ?>

<?php /* include_partial('ullTableTool/ullPagerTop', array(
  'pager'         => $pager, 
  'paging'        => $paging,
  'enable_export'  => false,
)) */?>

<?php if ($generator->getRow()->exists()): ?>

  <div class='ull_course_offering_list'>
  
  <?php foreach($generator->getForms() as $row => $form): ?>
  
    <?php $object = $form->getObject() ?>
  
    <div class="ull_course_offering_item" style="cursor:pointer;" onclick="document.location.href = 
        '<?php echo url_for('ullCourse/show?slug=' . $object->slug) ?>'">
    
      <div class="ull_course_offering_item_photo">
        <?php $photoWidget = new ullWidgetPhoto() ?>
        <?php echo $photoWidget->render(null, $object['Trainer']['photo']) ?>
      </div>
      
      <div class="ull_course_offering_item_content">
        <h2>
          <?php echo link_to($object->name, 'ullCourse/show?slug=' . $object->slug)?>
        </h2>
        <h3>
          <?php echo __('With', null, 'ullCourseMessages') ?>
          <?php echo $object->Trainer->first_name?> <?php echo $object->Trainer->last_name?>
        </h3>
        <p class="ull_course_offering_item_date">
        
          <?php if ($object->isMultiDay()): ?>
            <?php echo __('%units% units', array('%units%' => $object['number_of_units']), 'ullCourseMessages') ?>
            <?php echo __('starting at', null, 'ullCourseMessages') ?>
          <?php endif ?>
            
          <?php echo $form['begin_date']->render() ?>
          
          <?php echo __('from', null, 'common') ?>
          <?php echo $form['begin_time']->render() ?>
          <?php echo __('to', null, 'common')?>
          <?php echo $form['end_time']->render() ?>
          
          <?php ?>
        </p>
      </div>
    
    </div>
  <?php endforeach; ?>
  
  </div>
  
<?php endif ?>

<?php include_partial('ullTableTool/ullPagerBottom', array('pager' => $pager)) ?>

</div> 
<!-- end of "cms_content" -->

<?php use_javascripts_for_form($filter_form) ?>
<?php use_stylesheets_for_form($filter_form) ?>
<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>