<div id="ull_course_offering_quick_filter">

  <div class="ull_course_offering_quick_filter_category">
    <?php echo ull_link_to(
      __('Children', null, 'ullCourseMessages'), 
      array('filter[search]' => 'children')
    )?>
    <ul>
      <li>
        <?php echo ull_link_to(
          __('One-day', null, 'ullCourseMessages'), 
          array('filter[search]' => 'children one-day')
        )?>
      </li>
      <li>
        <?php echo ull_link_to(
          __('Several-day', null, 'ullCourseMessages'), 
          array('filter[search]' => 'children several-day')
        )?>
      </li>      
    </ul>  
  </div>
  
  <div class="ull_course_offering_quick_filter_category">
    <?php echo ull_link_to(
      __('Teenager', null, 'ullCourseMessages'), 
      array('filter[search]' => 'teenager')
    )?>
    <ul>
      <li>
        <?php echo ull_link_to(
          __('One-day', null, 'ullCourseMessages'), 
          array('filter[search]' => 'teenager one-day')
        )?>
      </li>
      <li>
        <?php echo ull_link_to(
          __('Several-day', null, 'ullCourseMessages'), 
          array('filter[search]' => 'teenager several-day')
        )?>
      </li>      
    </ul>  
  </div>
  
  <div class="ull_course_offering_quick_filter_category">
    <?php echo ull_link_to(
      __('Adult', null, 'ullCourseMessages'), 
      array('filter[search]' => 'adult')
    )?>
    <ul>
      <li>
        <?php echo ull_link_to(
          __('One-day', null, 'ullCourseMessages'), 
          array('filter[search]' => 'adult one-day')
        )?>
      </li>
      <li>
        <?php echo ull_link_to(
          __('Several-day', null, 'ullCourseMessages'), 
          array('filter[search]' => 'adult several-day')
        )?>
      </li>      
    </ul>  
  </div>    

</div>