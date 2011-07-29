<?php include_partial('global/html_head') ?>
<?php /* The statement above loads the html head */ ?>
<?php /* The file is located in apps/frontend/templates/_html_head.mobile.php */ ?>

<body class="<?php 
  echo sfInflector::underscore($sf_context->getModuleName()) . '_' . $sf_context->getActionName();
?>">

<div id="container">

    <div id="content">
      <?php echo $sf_data->getRaw('sf_content') ?>
    </div> <!-- end of content -->
     
</div> <!--  end of container -->

</body>

</html>