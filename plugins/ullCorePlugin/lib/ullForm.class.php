<?php

abstract class ullForm
{
  
  protected
  
    // name of the container containing the fields. usually a table_name or a ullFlowAppId
    $container_name   = ''

    //array holding field info (access, enabled, name, type, ...) 
    ,$fields_info      = array()

    //array holding the field's data and handler ($propel_object, formatting function and parameters)   
    ,$fields_data     = array()

    //
    ,$access_default   = 'r'
    
    // humanized system column  names
    , $system_column_names_humanized = array (
      'id'                    => 'ID'
      , 'creator_user_id'     => 'Created by' 
      , 'created_at'          => 'Created at'
      , 'updator_user_id'     => 'Updated by' 
      , 'updated_at'          => 'Updated at'
      , 'db_table_name'       => 'Table name'
      , 'db_column_name'      => 'Column name'
      , 'field_type'          => 'Field Type'
      , 'enabled'             => 'Enabled'
      , 'show_in_list'        => 'Show in list'
      , 'mandatory'           => 'Mandatory'
      , 'caption_i18n_default' => 'Caption'
      , 'description_i18n_default' => 'Description'
      , 'caption_i18n'        => 'Caption i18n'
      , 'description_i18n'    => 'Description i18n'
      , 'options'             => 'Options'
    )
  ;  
    
    
  abstract function buildFieldsInfo();
  
  
  abstract function retrieveFieldsData();

  
  /*
   * @param value_object          propel row object containing the value of the field
   * @param value_field string    propel column containing the field's data (field_name, or 'value' in ullFlow)
   * @param field_name string     unique field identifier (column_name or ullFlowFieldId)
   * @param field_type string     valid ull field type 
   * @param access string         r = read, w = write
   * @param options mixed         options for the field
   */
  
  protected function callFieldHandler(
    $value_object, 
    $value_field, 
    $field_name,
    $field_type,
    $access = 'r', 
    $options = '') {
    
    // use ullFieldType classes
//    $field_type = $this->fields_info[$field_name]['field_type'];
    
//    echo "<br />value_object:<br />";
//    ullCoreTools::printR($value_object);
//    echo "value_field: $value_field <br />";
//    echo "field_name: $field_name <br />";
//    echo "options: $options <br />";
//    echo "type: $field_type<br />";
        
    
    
    
    
    $field_type_class_name = 'ullFieldHandler' . sfInflector::camelize($field_type);
      
    if (class_exists($field_type_class_name)) {
      
//      echo "<br />field_type_class_name ($field_name): $field_type_class_name";
      
      $field_handler = new $field_type_class_name();
      
//      ullCoreTools::printR(get_class_methods($field));
              
      $field_handler->setPropelObject($value_object);
      
      $field_handler->setOptions($options);
      
//      ullCoreTools::printR($field_handler);

//      $access = $this->fields_info[$field_name]['access'];
      
//      ullCoreTools::printR($access);
      
      if ($access == 'r') {
        return $field_handler->getShowWidget($value_field);
        
      } else {
        return $field_handler->getEditWidget($value_field, $field_name);
        
      }                          
        
    } else {
      throw new sfException("class $field_type_class_name not found");
    }
  
  }
  
  
  public function setContainerName($value) {
    $this->container_name = $value;
  }
  
  public function getContainerName() {
    return $this->container_name;
  }

  
  public function setAccessDefault($value) {
    $this->access_default = $value;
  }
  
  public function getAccessDefault() {
    return $this->access_default;
  }

  
  public function setFieldsInfo($value) {
    $this->fields_info = $value;
  }
  
  public function getFieldsInfo() {
    return $this->fields_info;
  } 

  
  public function setFieldsData($value) {
    $this->fields_data = $value;
  }
  
  public function getFieldsData() {
    return $this->fields_data;
  }

  public function getFieldsDataOne() {
    return $this->fields_data[0];
  }
  
}

?>