<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>
<?php $generator = $sf_data->getRaw('generator') ?>

<!-- <h1><?php echo __('Wiki Result List'); ?></h1> -->


<?php
  echo ull_form_tag('ullWiki/list', array(
      'class' => 'inline',
      'name' => 'ull_wiki_search_form')
  );
?>

<ul class='ull_action'>
    <li><?php echo ull_button_to(__('Create', null, 'common'), 'ullWiki/create'); ?></li>

    <li>
     <?php echo $filter_form['search']->renderLabel() ?>    
     <?php echo $filter_form['search']->render() ?>
     <?php echo submit_image_tag(ull_image_path('search'),
              array('alt' => 'search_list')) ?>
    </li> 
</ul>

</form>

<br />

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager)
      ); ?>

<br />

<?php if ($generator->getRow()->exists()): ?>
  <table class='result_list'>
  
  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator' => $generator,
      'order'     => $order,
      'order_dir' => $order_dir,
  )); ?>
  
  <!-- data -->
  
  <tbody>
  <?php $odd = false; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    <?php $identifier = $generator->getIdentifierUrlParams($row) ?>
    <?php $form['subject']->getWidget()->setAttribute('href', 
      ull_url_for(array_merge($generator->getIdentifierUrlParamsAsArray($row), array('action' => 'show')))) ?>
      <?php
        if ($odd) {
          $odd_style = ' class=\'odd\'';
          $odd = false;
        } else {
          $odd_style = '';
          $odd = true;
        }
      ?>
    <tr <?php echo $odd_style ?>>
      <td>          
        <?php
            echo ull_icon(
              'ullWiki/edit?' . $identifier
              , 'edit'
              , __('Edit', null, 'common')
            );
        
            echo ull_icon(
              'ullWiki/delete?' . $identifier
              , 'delete'
              , __('Delete', null, 'common')
              , 'confirm='.__('Are you sure?', null, 'common')
            );
        ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
<?php endif ?>