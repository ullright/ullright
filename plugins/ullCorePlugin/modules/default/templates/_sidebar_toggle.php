<?php use_javascript('/ullCorePlugin/js/jq/jquery-min.js')?>
<?php use_javascript('/ullCorePlugin/js/jq/jquery-ui-min.js')?>
<?php use_javascript('/ullCorePlugin/js/sidebar.js')?>
<?php $hideSidebar = $sf_user->getAttribute('sidebar_hidden', false); ?>

<script type="text/javascript">
  //<![CDATA[
  $(document).ready(function() {
    $("#sidebar").before(
      '<div id="sidebar_tab">' +
      '<a href="" id="sidebar_button_in" class="sidebar_round_button no_underline"><big>&rarr;</big></a>' +
      '<a href="" id="sidebar_button_out" class="sidebar_round_button no_underline"><big>&larr;</big></a>' +
      '</div>'
    );
      
    enableSidebar(<?php echo $hideSidebar ? 'true' : 'false' ?>, $('#sidebar_button_out'), $('#sidebar_button_in'), $('div#sidebar'), $('div#canvas'));
  });
  //]]>
</script>