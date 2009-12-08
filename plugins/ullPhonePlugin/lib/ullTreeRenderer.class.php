<?php

class ullTreeRenderer
{
  protected
    $node,
    $widgetPhoto
  ;
  
 
  public function __construct(ullTreeNodeOrgchart $node)
  {
    $this->node = $node;
    
    $this->widgetPhoto = new ullWidgetPhoto();
  }
  
  
  public function __toString()
  {
    return (string) $this->render();
  }
  
  
  public function render()
  {
    $return = '';
    $return .= '<div class="ull_orgchart">';
    $return .= $this->doRendering($this->node);
    $return .= '</ul>';
    
    return $return;
  }
  
  
  public function doRendering(ullTreeNode $node)
  {
    $return = '';
    
    $return .='
  <table cellpadding="0" cellspacing="0">
    <tbody>
    
      <tr class="ull_orgchart_head">
        <td>
    ';
    
    if ($node->getLevel() == 1 && $superior = $node->getData()->superior_ull_user_id)
    {
      $return .= '<div class="ull_orgchart_arrow">' 
        . ull_link_to(
            '&uArr;', 
            array('user_id' => $superior),
            array('title' => __('One level up', null, 'ullOrgchartMessages'))
          )
        . '</div>';
    }
    
    $return .= $this->renderBoxSuperior($node->getData());
    
    $return .= '
        </td>
      </tr>
    ';
        
   
    // Subordinates
    if ($node->hasSubordinates())
    {
      $return .= '
      <tr class="ull_orgchart_subordinates">
        <td>
          <table cellpadding="0" cellspacing="0">      
      ';
      
      $return .= $this->renderSpacerRow();
      
      // dual column mode
      if ($node->getLevel() == 1)
      {
        $pairs = array();
        $pairNumber = 0;
        $numOfSubordinates = count($node->getSubordinates());
        
        // special handling if only one result -> print on the right side
        if ($numOfSubordinates == 1)
        {
          $subs = $node->getSubordinates();
          $pairs[]['even'] = reset($subs);
        }
        else
        {
          foreach($node->getSubordinates() as $subordinate)
          {
            if (isset($pairs[$pairNumber]['odd']))
            {
              $pairs[$pairNumber]['even'] = $subordinate;
              $pairNumber++;
            }
            else
            {
              $pairs[$pairNumber]['odd'] = $subordinate;
            }  
          }
        }
        $numOfPairs = count($pairs);
        
        foreach($pairs as $currentPairNumber => $pair)
        {
          $return .= '
            <tr class="ull_orgchart_boxes_row">
              <td rowspan="2">
          ';
          
          if ($numOfSubordinates == 1)
          {
            $return .= $this->renderBoxSubordinatePlaceholder();
          }
          else
          {
            $return .= $this->renderBoxSubordinate($pair['odd']->getData());
          }
          
          $return .= '                 
              </td>
              <td class="ull_orgchart_border_right ' . ((isset($pair['odd'])) ? 'ull_orgchart_border_double_bottom' : '') . '">
                <div class="ull_orgchart_spacer_in_between">&nbsp;</div>
              </td>
              <td class="ull_orgchart_border_left ' . ((isset($pair['even'])) ? 'ull_orgchart_border_double_bottom' : '') . '">
                <div class="ull_orgchart_spacer_in_between">&nbsp;</div>
              </td>                
              <td rowspan="2">
          ';
          
          if (isset($pair['even']))
          {
            $return .= $this->renderBoxSubordinate($pair['even']->getData());
          }
          else
          {
            $return .= '&nbsp;';
          }

          $return .= '
              </td>
            </tr>        
            <tr>
              <td class="ull_orgchart_spacer_in_between ' 
            . (($currentPairNumber + 1 < $numOfPairs || $node->hasSubnodes()) ? 'ull_orgchart_border_right' : '') 
            . '">&nbsp;</td>
              <td class="ull_orgchart_spacer_in_between ' 
            . (($currentPairNumber + 1 < $numOfPairs || $node->hasSubnodes()) ? 'ull_orgchart_border_left' : '') 
            . '">&nbsp;</td>                
            </tr>           
          
          ';
            
          if ($currentPairNumber + 1 < $numOfPairs)
          {
            $return .= $this->renderSpacerRowThin();
          }          
        }  
      }
      
      // single column mode
      else
      {
        foreach($node->getSubordinates() as $subordinate)
        {
          $return .= '
              <tr class="ull_orgchart_boxes_row">
                <td rowspan="2">
                  &nbsp;
                </td>
                <td class="ull_orgchart_border_right">
                  <div class="ull_orgchart_spacer_in_between">&nbsp;</div>
                </td>
                <td class="ull_orgchart_border_left ull_orgchart_border_double_bottom">
                  <div class="ull_orgchart_spacer_in_between">&nbsp;</div>
                </td>                
                <td rowspan="2">
          ';
          $return .= $this->renderBoxSubordinate($subordinate->getData());
          $return .= '
                </td>
              </tr>        
              <tr>
                <td class="ull_orgchart_spacer_in_between ' . (($node->hasSubnodes() || !$subordinate->isLast()) ? 'ull_orgchart_border_right' : '') . '">&nbsp;</td>
                <td class="ull_orgchart_spacer_in_between ' . (($node->hasSubnodes() || !$subordinate->isLast()) ? 'ull_orgchart_border_left' : '') . '">&nbsp;</td>                
              </tr>          
          
          ';
          
          if (!$subordinate->isLast())
          {
            $return .= $this->renderSpacerRowThin();
          }
        }
      }
      
      if ($node->hasSubnodes())
      {
        $return .= $this->renderSpacerRow();
      }      
      
      $return .= '
          </table>
        </td>
      </tr> 
      ';      
    }
    

      
    
    // Sub superiors
    if ($node->hasSubnodes())
    {
      $return .= '
      <tr class="ull_orgchart_sub_superiors">
        <td>
          <table cellpadding="0" cellspacing="0">       

            <tr class="ull_orgchart_spacer_row">
      ';
      
      foreach($node->getSubnodes() as $subnode)
      {
        $return .= '
              <td class="ull_orgchart_border_right ' . ((!$subnode->isFirst()) ? 'ull_orgchart_border_top' : '') . '">&nbsp;</td>
              <td class="ull_orgchart_border_left ' . ((!$subnode->isLast()) ? 'ull_orgchart_border_top' : '') . '">&nbsp;</td>
        ';        
      }
      
      $return .= '
            </tr>
            
            <tr class="ull_orgchart_sub_superiors_row">
      ';
      
      foreach($node->getSubnodes() as $subnode)
      {
        $return .= '
              <td colspan="2" class="ull_orgchart_sub_superiors_row_td">
        ';
        if ($subnode->hasSubnodes() || $subnode->hasSubordinates())
        {
          $return .= $this->doRendering($subnode);
        }
        else
        {
          $return .= $this->renderBoxSuperior($subnode->getData());
          
          $return .= '<div class="ull_orgchart_arrow">' 
            . ull_link_to(
                '&dArr;', 
                array('user_id' => $subnode->getData()->id),
                array('title' => __('One level down', null, 'ullOrgchartMessages'))                
              )
            . '</div>';
        }
        
        $return .= '
              </td>
        ';
        
        
      }

      $return .= '
          </table>
        </td>
      </tr>      
      ';
    }
    

    $return .= '
    </tbody>
  </table>      
    ';
    
    return $return;
  }

  
  public function renderBox(UllEntity $entity, $cssStyle)
  {
    $return = '';
    $return .= '
          <div class="' . $cssStyle . '">
    ';
    $return .= $this->widgetPhoto->render(null, $entity->photo, array('class' => 'ull_orgchart_photo', 'align' => 'right'));
    $return .= '<ul class="ull_orgchart_box_list">';
    $return .= '<li><em>' . ull_link_to($entity, array('user_id' => $entity->id)) . '</em></li>';
    $return .= '<li>' . $entity->UllLocation . '</li>';
    $return .= '<li>' . $entity->UllDepartment . '</li>';
    $return .= '</ul>';
    $return .= '          
          </div>
    '; 
    
    return $return;
  } 
  
  
  public function renderBoxSuperior(UllEntity $entity)
  {
    return $this->renderBox($entity, 'ull_orgchart_box_head');
  }
  
  
  public function renderBoxSubordinate(UllEntity $entity)
  {
    return $this->renderBox($entity, 'ull_orgchart_box_subordinate');
  }
  
  
  public function renderBoxSubordinatePlaceholder()
  {
    $return = '';
    $return .= '
          <div class="ull_orgchart_box_subordinate_placeholder">
    ';
    $return .= '          
          </div>
    '; 
    
    return $return;    
  }  
  
  
  public function renderSpacerRow($cssClass = 'ull_orgchart_spacer_row')
  {
    return '
            <tr class="' . $cssClass . '">
              <td>&nbsp;</td>
              <td class="ull_orgchart_border_right">&nbsp;</td>
              <td class="ull_orgchart_border_left">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>     
    ';
  }
  
  public function renderSpacerRowThin()
  {
    return $this->renderSpacerRow('ull_orgchart_spacer_row_thin');
  }
  
  
  
  
}