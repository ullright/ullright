<?php //echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>


<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
    <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator' => $generator,
      'order'     => 'created_at',
      'order_dir' => 'ASC',
      'add_icon_th' => false,
  )); ?>
  
  <!-- data -->
  <tbody>
  <?php $odd = true; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
    <?php $idAsArray = (array) $generator->getIdentifierUrlParamsAsArray($row); ?>
    <tr <?php echo ($odd) ? $odd = '' : $odd = 'class="odd"' ?>>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>

  <?php if ($generator->getCalculateSums()): ?>
    <tr class="list_table_sum">
      <?php echo $generator->getSumForm() ?>
    </tr>
  <?php endif ?>
  
  </tbody>
  </table>
<?php endif ?>