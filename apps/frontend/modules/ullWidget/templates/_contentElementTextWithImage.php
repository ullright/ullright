<?php $values = $sf_data->getRaw('values') ?>

<h1><?php echo $values['headline'] ?></h1>
<?php echo image_tag($values['image']) ?>
<?php echo auto_link_text($values['body']) ?>