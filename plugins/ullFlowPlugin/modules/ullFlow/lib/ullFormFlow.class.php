<?php

class ullFormFlow extends ullForm
{
  
  protected
    $doc;
  
  function buildFieldsInfo() {
    
    $user_id  = sfContext::getInstance()->getUser()->getAttribute('user_id');
       
    $c = new Criteria();
    $c->add(UllFlowFieldPeer::ULL_FLOW_APP_ID, $this->container_name);
    $c->addAscendingOrderByColumn(UllFlowFieldPeer::SEQUENCE);
    $fields = UllFlowFieldPeer::doSelect($c); 

    foreach ($fields as $field) {
      
//      ullCoreTools::printR($field);

      $field_id = $field->getID();
      $field_name  = 'field' . $field_id;

      $this->fields_info[$field_name]['default_value'] =
        $field->getDefaultValue();

      if ($field->getIsTitle()) {
        $this->fields_info[$field_name]['is_subject']        = true;
      }
      
      if ($field->getIsPriority()) {
        $this->fields_info[$field_name]['is_priority']      = true;
      }
      
      if ($field->getIsDeadline()) {
        $this->fields_info[$field_name]['is_deadline']      = true;
      }
      
      if ($field->getIsCustomField1()) {
        $this->fields_info[$field_name]['is_custom_field1'] = true;
      }   
      
//      echo "<br />$column_name: " . $this->column_info[$column_name]['access'];
      
      // add humanized column name to column_info  
      $this->fields_info[$field_name]['name_humanized'] = 
        ullCoreTools::getI18nField($field, 'caption');
        
//      ullCoreTools::printR($this->fields_info[$field_name]['name_humanized']);
//      ullCoreTools::printR($field_name);
//      ullCoreTools::printR($field);
      
//      $this->fields_info[$field_name]['field_type'] = 'text';

      
//      // check for primary key ==> only for list action, but doesn't hurt for edit
//      if ($column->isPrimaryKey()) {
//        $this->column_info[$column_name]['primary_key']   = true;
//        $this->column_info[$column_name]['access']        = 'r';
//      } 
      
      
      $this->fields_info[$field_name]['field_type'] = 
        UllFieldPeer::retrieveByPK($field->getUllFieldId())->getFieldType();
        
      $ull_field = UllFlowFieldPeer::retrieveByPK($field_id);
        
      $this->fields_info[$field_name]['options'] = 
        $ull_field->getOptions();
      

      $this->fields_info[$field_name]['mandatory'] = 
        $ull_field->getMandatory();


        

      // == per field access
      $access_group_id = $ull_field->getUllAccessGroupId(); 

      if ($access_group_id) {
        $user_ids_read = UllAccessGroupGroupPeer::retrieveUserIdsByAccessGroupId($access_group_id, 'read'); 
        if (in_array($user_id, $user_ids_read)) {
          $this->fields_info[$field_name]['access'] = 'r';
        }
        
        if ($this->access_default == 'w') {
          $user_ids_write = UllAccessGroupGroupPeer::retrieveUserIdsByAccessGroupId($access_group_id, 'write'); 
          if (in_array($user_id, $user_ids_write)) {
            $this->fields_info[$field_name]['access'] = 'w';
          }
        }
       
      } else {
        // if no acces group specified -> use default access
        $this->fields_info[$field_name]['access'] = $this->access_default;
      }

      // enabled?
      if (!$ull_field->getEnabled()) {
        unset($this->fields_info[$field_name]['access']);
      }


    } // end of loop through columns   
       
    
//    ullCoreTools::printR($this->fields_info);
    
  }      

  
  
  function retrieveFieldsData() {
    // loop through columns  
    foreach ($this->fields_info as $field_name => $field) {
      
      // field enabled?
      if (isset($field['access'])) {

        $c = new Criteria();
        $c->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->doc);
        $c->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, str_replace('field','',$field_name));
        $c->add(UllFlowValuePeer::CURRENT, true);
        $value_object = UllFlowValuePeer::doSelectOne($c);
        
        if (!$value_object) {
          $value_object = new UllFlowValue();
        }
              
  //      $value = $value_obj->getValue();
  
//        $options = $field['options'];
//  
//        $array[$field_name] = 
//          $this->callFieldHandler($value_object, 'value', $field_name, $options);



        $array[$field_name] = 
          $this->callFieldHandler(
            $value_object
            , 'value'
            , $field_name
            , $field['field_type']
            , $field['access']
            , @$field['options']
            , $field['default_value']
          );
        // rename field (numeric html field names are not allowed)
        $array[$field_name]['parameters']['options']['name']          = $field_name;
        $array[$field_name]['parameters']['options']['id']            = $field_name;
      }
    }      

    $this->fields_data[] = @$array; 
//print_r($this->fields_data);
  }

  public function setDoc($value) {
    $this->doc = $value;
  }
  
  public function getDoc() {
    return $this->doc;
  }    
  
}

?>