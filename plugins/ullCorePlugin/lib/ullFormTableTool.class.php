<?php

class ullFormTableTool extends ullForm
{
  
  protected
    $value_object;
    
  
  function buildFieldsInfo() {
    
    // === get table information
    $table_class = sfInflector::classify($this->container_name);
    
    //$map_builder = call_user_func($table_class . 'Peer::getMapBuilder');
    $map_builder = call_user_func(array($table_class . 'Peer', 'getMapBuilder'));
    
    // === check for i18n table
    $table_name_i18n  = $this->container_name . '_i18n';
    $table_class_i18n = sfInflector::classify($table_name_i18n);
    
    if (class_exists($table_class_i18n)) {
      
      // can be called this 'overwriting' way, because already loaded maps are preserved, and the new 
      //  one (in this case the .._i18n map) is added
      $map_builder = call_user_func(array($table_class_i18n . 'Peer', 'getMapBuilder'));
    
//      $this->class_name_i18n = $class_name_i18n;
    } 
    
 

    // getTables is a method of propel's DatabaseMap.php
    $fields = $map_builder->getDatabaseMap()->getTable($this->container_name)->getColumns();

//    ullCoreTools::printR($fields);

    if (
      class_exists($table_class_i18n)
      // only add i18n columns if the current culture isn't the base default culture (usually english)
      && (sfConfig::get('base_default_language', 'en') <> substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2))
      ) {
        
      $fields_i18n = $map_builder->getDatabaseMap()->getTable($table_name_i18n)->getColumns();
//      ullCoreTools::printR($this->columns_i18n);
      
      // remove ID and CULTURE columns from 18n fields as they are not needed
      unset($fields_i18n['ID']);
      unset($fields_i18n['CULTURE']);
      
      // TODO: sort i18n columns right after default columns?
      $fields = array_merge($fields, $fields_i18n);
      
    }    
    
   

    foreach ($fields as $field) {
      
//      ullCoreTools::printR($field);

      $field_name   = strtolower($field->getColumnName());
      $table_name   = $field->getTableName(); // to differenciate between normal and 18n tables      
            
      
      /*
       * set defaults
       */
      
      // set default access type (read, write, none)
      $this->fields_info[$field_name]['access']        = $this->access_default;
      
      $this->fields_info[$field_name]['enabled']       = true;
      $this->fields_info[$field_name]['show_in_list']  = true;
          
      // add standard humanized column name to column_info  
      $this->fields_info[$field_name]['name_humanized'] = sfInflector::humanize($field_name);
           
      // load i18n'zed system column_name translations
      if (@$this->system_column_names_humanized[$field_name]) {
        $this->fields_info[$field_name]['name_humanized'] 
          = __($this->system_column_names_humanized[$field_name], null, 'common');
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

      
      // == per database field_type
      /*
       * Creole Types:
       * 1  = boolean? or char?
       * 5  = integer
       * 7  = string (varchar?)
       * 10 = date
       * 12 = datetime
       */
      
      $field_creole_type = $field->getCreoleType();
      
      if ($field_creole_type == '10') {
        $this->fields_info[$field_name]['field_type']    = 'date';
      }
      
      if ($field_creole_type == '1') {
        $this->fields_info[$field_name]['field_type']    = 'checkbox';
      }
      
//      echo "<br />$field_name: type: " . $field->getCreoleType();

    } // end of loop through propel fields   
      
      

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
              
//        ullCoreTools::printR($column_info_row);      
        
        $field_name         = $column_info_row->getDbColumnName();
        
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

      } // end of loop through ull_column_info columns
          
    }        
 
//    ullCoreTools::printR($this->fields_info);
    
  }      

  
  
  
  
  function retrieveFieldsData() {
    
    // set culture to allow transparent access to i18n fields
    if (method_exists($this->value_object, 'setCulture')) { 
      $this->value_object->setCulture(substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
    }    
    
    // loop through fields  
    foreach ($this->fields_info as $field_name => $field) { 
      
//      $method_name = 'get' . sfInflector::camelize($field_name);
      
//      $array[$field_name] = 
//        $this->callFieldHandler($this->value_object, $field_name, $field_name);
        
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
  
}

?>
