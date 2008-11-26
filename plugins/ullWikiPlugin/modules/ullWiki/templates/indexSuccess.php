<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml();
//$sf_user->setCulture('de');
?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <ul class="tc_tasks tc_tasks_format">
        <li><?php echo link_to(image_tag("/ullWikiThemeNGPlugin/images/ull_wiki_32x32",
        'alt=' . __('Create', null, 'common')), 'ullWiki/create') . 
        link_to(__('Create', null, 'common'), 'ullWiki/create')?></li>
      </ul>
    </div>
    
    <div id="tc_search">
      <div class="tc_search_quick_box color_light_bg">
        <?php echo ull_form_tag(array('action' => 'list')); ?>
		      <div class="tc_search_quick_box_label">
		        <?php echo $form['search']->renderLabel() ?>     
		        <?php echo $form['fulltext']->renderLabel() ?>
		      </div>
		      <div class="tc_search_quick_box_element">
		        <?php echo $form['search']->render() ?>
		        <?php echo submit_image_tag(ull_image_path('search', null, null, 'ullWiki'), array('class' => 'tc_search_quick_box_img')) ?>
		      </div>
		      <br />
          <div class="tc_search_quick_box_element">
		        <?php echo $form['fulltext']->render() ?>
		      </div>
		      </form>
		    </div>
      <div class="tc_search_tag_box color_light_bg"><br /><br /><br /><br />tba<br /></div>
    </div>
    
    <div id="tc_queries">
      <div class="tc_query_box color_light_bg">
	      <ul>
	        <li><?php echo ull_link_to(__('New entries', null, 'common'), array('action' => 'list')) ?></li>
	        <li><?php echo ull_link_to(__('Ordered by subject', null, 'common'), array('action' => 'list', 'sort' => 'subject')) ?></li>
	      </ul>
	    </div>
      <div class="tc_query_box color_light_bg"><br />tba<br /></div> 
    </div>
  </div>
  <div id="tc_footer">
     <!-- add footer here -->
  </div>
</div>

<!--   

<h1><?php echo __('Wiki') . ' ' . __('Home', null, 'common'); ?></h1>

<h4><?php echo __('Search', null, 'common'); ?></h4>
<ul>
  <li>
    <?php echo ull_form_tag(array('action' => 'list')); ?>

      <?php echo $form['search']->render() ?>
      &nbsp;
      <input type="submit" name="commit" value="<?php echo __('Search', null, 'common') . ' &gt;' ?>"
        title="<?php echo __('Searches for ID, subject and tags', null, 'common') ?>" />
      <br />
      <?php echo $form['fulltext']->render() ?>
      <?php echo $form['fulltext']->renderLabel() ?>
    </form>
  </li>
</ul>

<br />

<h4><?php echo __('Actions', null, 'common'); ?></h4>
<ul>
  <li><?php echo link_to(__('Create', null, 'common'), 'ullWiki/create') ?></li>
</ul>

<h4><?php echo __('Queries', null, 'common'); ?></h4>
<ul>
  <li><?php echo ull_link_to(__('New entries', null, 'common'), array('action' => 'list')) ?></li>
  <li><?php echo ull_link_to(__('Ordered by subject', null, 'common'), array('action' => 'list', 'sort' => 'subject')) ?></li>
</ul>
--> 
<?php
//$culture = $sf_user->getCulture();
//
//echo "culture: $culture";

?>
