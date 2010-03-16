<?php slot('sidebar') ?>

<div id="sidebar_ull_cms">
  <div class="sidebar_section">
    <ul class="sidebar_list">
      <li>Über uns</li>
      <li>
        <ul class="sidebar_list">
          <li>Team</li>
        </ul>
      </li>
    </ul>
  </div>
</div>

<?php end_slot() ?>


<h3><?php echo $doc->title ?></h3>

<?php echo $doc->getBody(ESC_RAW) ?> 