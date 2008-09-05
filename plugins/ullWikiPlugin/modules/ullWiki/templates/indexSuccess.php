<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml();
//$sf_user->setCulture('de');
?>



<h1><?php echo __('Wiki') . ' ' . __('Home', null, 'common'); ?></h1>

<h4><?php echo __('Search', null, 'common'); ?></h4>
<ul>
  <li>
  <?php echo ull_reqpass_form_tag(array('action' => 'list')); ?>

  <table style="float: left;">
    <?php echo $form ?>
  </table>

  <table>
    <tr>
      <td>
        <?php echo submit_tag(__('Search', null, 'common'),
                              'title = ' . __('Searches for ID, subject and tags', null, 'common')) ?>
      </td>
    </tr>
  </table>
  </li>
</ul>
</form>

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
