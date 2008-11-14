<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml();
//$sf_user->setCulture('de');
?>



<h1><?php echo __('Wiki') . ' ' . __('Home', null, 'common'); ?></h1>

<h4><?php echo __('Search', null, 'common'); ?></h4>
<ul>
  <li>
    <?php echo ull_form_tag(array('action' => 'list')); ?>

      <?php echo $form['search']->render() ?>
      &nbsp;
      <input type="submit" name="commit" value="<?php __('Search', null, 'common') ?>"
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

<?php
//$culture = $sf_user->getCulture();
//
//echo "culture: $culture";

?>
