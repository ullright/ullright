<?php
/**
 * This widget provides support for displaying a many to many
 * relationship (i.e. where m records are linked to n records
 * from a different model via an association table).
 * 
 * At the moment it just outputs a comma separated list of
 * all associated records.
 * 
 * See also: ullWidgetManyToManyWrite
 * 
 * This widget supports Doctrine::HYDRATE_ARRAY hydration.
 * This widget ignores the 'table_method' option (can be added if needed).
 * When using array hydration, the 'method' and 'key_method' options
 *   are interpreted as key and value column names (i.e. instead of
 *   ->method(), [method] is used).
 */
class ullWidgetManyToManyRead extends ullWidget
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('method', '__toString');
    $this->addOption('key_method', 'getPrimaryKey');
    $this->addOption('query', null);
    
    parent::configure($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //handle case: no associated records
    if (!count($value))
    {
      return __('None', null, 'common');
    }

    //cloning is needed because otherwise there'd be only one
    //query object for all rows (e.g. during list view), and we
    //add a where clause to the query later on
    $originalQuery = $this->getOption('query');
    $q = ($originalQuery !== null) ? clone $originalQuery : null;
    $method = $this->getOption('method');
    $keyMethod = $this->getOption('key_method');

    if ($q === null)
    {
      $model = $this->getOption('model');
      
      $q = new Doctrine_Query();
      $q
        ->from($model)
        ->select($method)
        //better performance
        ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
      ;
    
      if ($order = $this->getOption('order_by'))
      {
        $q->orderBy($order);
      }
    }

    //add currently associated id-values as filter for query
    $q->andWhereIn($keyMethod, array_values($value));
    $records = $q->execute();
    
    //build comma separated list out of retrieved records
    $listParts = array();
    
    //was array hydration used?
    if (is_array($records))
    {
      foreach ($records as $record)
      {
        $listParts[] = $record[$method];
      }
    }
    //otherwise assume records
    else
    {
      foreach ($records as $record)
      {
        $listParts[] = $record->$method();
      }
    }
    
    //use default (= ullWidget) rendering for the record list
    return parent::render($name, implode(', ', $listParts), $attributes, $errors);
  }
}