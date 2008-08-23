<?php

class ullFormFlowDoc extends ullForm
{
  protected
    $app
  ;
  
  function buildFieldsInfo() {
        
    // === get table information
    $table_class = sfInflector::classify($this->container_name);
    
    $map_builder = call_user_func(array($table_class . 'Peer', 'getMapBuilder'));
    
    // getTables is a method of propel's DatabaseMap.php
    $fields = $map_builder->getDatabaseMap()->getTable($this->container_name)->getColumns();
    
//    ullCoreTools::printR($fields);
//    exit();

    if ($this->app and $default_list_columns = $this->app->getDefaultListColumns()) {
      $list_columns = explode(',', $default_list_columns);
    } else {
      $list_columns = array (
        'id'
        , 'ull_flow_app_id'
        , 'title'
        , 'priority'
//        , 'custom_field1'
  //      , 'ull_flow_action_id'       
        , 'creator_user_id'
        , 'created_at'
  //      , 'updator_user_id'
  //      , 'updated_at'
//        , 'assigned_to_ull_user_id'
      );
    }
    
    
    
    
    /* filter fields and put them in the defined sequence*/
    $fields_filtered = array();
    foreach ($list_columns as $list_column) {
      $upper_field_name = strtoupper($list_column); 
      if (isset($fields[$upper_field_name])) {
          $fields_filtered[$upper_field_name] = $fields[$upper_field_name];
      }
    }
    
//    ullCoreTools::printR($fields);
//    exit();
    
    foreach ($fields_filtered as $field) {
      
      $field_name   = strtolower($field->getColumnName());
      
      /*
       * set defaults
       */
      
      // set default access type (read, write, none)
      $this->fields_info[$field_name]['access']        = $this->access_default;
                
      // add standard humanized column name to column_info  
      $this->fields_info[$field_name]['name_humanized'] = sfInflector::humanize($field_name);

      // load i18n'zed system column_name translations
      if (isset($this->system_column_names_humanized[$field_name])) {
        $this->fields_info[$field_name]['name_humanized'] 
          = __($this->system_column_names_humanized[$field_name], null, 'common');
      }
      
      // load column name for custom_fields
      if ($field_name == 'custom_field1') {
        $c = new Criteria();
        $c->add(UllFlowFieldPeer::IS_CUSTOM_FIELD1, true);
        $ull_flow_field = UllFlowFieldPeer::doSelectOne($c);
        if ($ull_flow_field) {
          $this->fields_info[$field_name]['name_humanized'] = ullCoreTools::getI18nField($ull_flow_field, 'caption');
        }
      }
      
      $this->fields_info[$field_name]['field_type'] = 'text';
      
            // check for primary key ==> only for list action, but doesn't hurt for edit
      if ($field->isPrimaryKey()) {
        $this->fields_info[$field_name]['primary_key']   = true;
        $this->fields_info[$field_name]['access']        = 'r';
      } 
      
      // == per field name
      
      // add automagically default field_types to field_types
      if (in_array($field_name, array('creator_user_id', 'updator_user_id'))) {
        $this->fields_info[$field_name]['field_type']    = 'user';
        $this->fields_info[$field_name]['access']        = 'r';
        $this->fields_info[$field_name]['show_in_list']  = false;
      }
      
      if (in_array($field_name, array('created_at', 'updated_at'))) {
        $this->fields_info[$field_name]['field_type']    = 'date';
        $this->fields_info[$field_name]['access']        = 'r';
        $this->fields_info[$field_name]['show_in_list']  = false;
      }      
      
      // priority etc
      if (in_array($field_name, array('priority', 'deadline', 'custom_field1'))) {
        
        // magic type to allow special handling in retrieve Data
        $this->fields_info[$field_name]['tabular']   = true;
        
      }

    } // end of loop through columns   
    
    
    
    /*
     * Check for external column info in table 'ull_column_info'
     */
    $column_info_class_name = sfInflector::classify('ull_column_info');
    
    if (class_exists($column_info_class_name)) {
      
      // get column info for current table
      $c = new Criteria();
      $c->add(
        constant($column_info_class_name . 'Peer::DB_TABLE_NAME')
        , $this->container_name
      );
      $column_info_rows = call_user_func(
          array($column_info_class_name . 'Peer', 'doSelect')
          , $c
      );

//      ullCoreTools::printR($column_info_rows);
//      exit();   
      
      foreach ($column_info_rows as $column_info_row) {
        
        if (method_exists($column_info_row, 'setCulture')) { 
          $column_info_row->setCulture(substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
        }
              getFieldsDataOne();
//        ullCoreTools::printR($column_info_row);      
        
        $field_name         = $column_info_row->getDbColumnName();
        
        //filter again
        
        if (in_array($field_name, $list_columns)) {
        
          // overwrite default field attributes
          
          $this->fields_info[$field_name]['field_type'] =
            UllFieldPeer::getFieldTypeByID($column_info_row->getUllFieldId());
  //          UllFieldPeer::retrieveByPK($column_info_row->getUllFieldId())->getFieldType();
            
          $this->fields_info[$field_name]['enabled'] = 
            $column_info_row->getEnabled();
            
          $this->fields_info[$field_name]['show_in_list'] =
            $column_info_row->getShowInList();
            
          $this->fields_info[$field_name]['mandatory'] =
            $column_info_row->getMandatory();          
          
          // don't overwrite with empty captions
          if ($field_caption = ullCoreTools::getI18nField($column_info_row, 'caption')) {
            $this->fields_info[$field_name]['name_humanized'] = $field_caption;
          }
        
        } //end of column filter

      } // end of loop through ull_column_info columns
          
    }            
    
       
  }      

  
  
  function retrieveFieldsData() {
    
    // set culture to allow transparent access to i18n fields
    if (method_exists($this->value_object, 'setCulture')) { 
      $this->value_object->setCulture(substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
    }    
    
    // loop through fields  
    foreach ($this->fields_info as $field_name => $field) { 
      
//      $method_name = 'get' . sfInflector::camelize($field_name);

//      ullCoreTools::printR($field_name);

      // special handling for tabular list types
      if (@$field['tabular'] == true) {
        
//          ullCoreTools::printR($this->value_object);
//        ullCoreTools::printR($field_name);
        
      
        // get is_... field from UllFlowField
        $c = new Criteria();
        $c->add(UllFlowFieldPeer::ULL_FLOW_APP_ID, $this->value_object->getUllFlowAppId());
        $c->add(constant('UllFlowFieldPeer::IS_' . strtoupper($field_name)), true);
        $f = UllFlowFieldPeer::doSelectOne($c);
        
//        ullCoreTools::printR($this->value_object->getUllFlowAppId());
//        exit();
        
        if ($f) {
          $field['field_type'] = 
            UllFieldPeer::retrieveByPK($f->getUllFieldId())->getFieldType();
//          $this->fields_info[$field_name]['field_type'] = $field['field_type'];
           
          $field['options'] = $f->getOptions();
//          $this->fields_info[$field_name]['options'] = $field['options'];

        } else {
          $field['field_type'] = 'text';
//          $this->fields_info[$field_name]['field_type'] = $field['field_type'];
        }
        
      }
      
      $array[$field_name] = 
        $this->callFieldHandler(
          $this->value_object
          , $field_name
          , $field_name
          , $field['field_type']
          , $field['access']
          , @$field['options']
        );
      
    }    

    $this->fields_data[] = $array;

  }

  
  public function setValueObject($value) {
    $this->value_object = $value;
  }
  
  public function getValueObject() {
    return $this->value_object;
  } 

  public function setApp($value) {
    $this->app = $value;
  }
  
}

?>