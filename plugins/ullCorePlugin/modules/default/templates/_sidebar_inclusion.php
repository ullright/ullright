    <div id="sidebar_content">
      <?php if (has_slot('sidebar')): ?>
        <?php include_slot('sidebar') ?>
      <?php endif ?>
      
      <?php include_component('default', 'sidebar') ?>
          
      <div id="sidebar_custom">
        <?php try { include_partial('myModule/custom_sidebar'); } catch (Exception $e) {} ?>
      </div>
    </div>