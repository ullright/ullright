<?php

/**
 * This class provides build support for search forms.
 * It's meant to be used like the existing generator
 * framework, however, this class does NOT inherit from
 * ullGenerator, since code intersection is not high
 * enough to warrant a forced inheritance structure.
 * 
 * Note that plugins which rely on virtual columns need
 * to inherit from this class, see the
 * ullFlowSearchGenerator for a reference implementation.
 */
class ullSearchGenerator extends ullGeneratorBase
{
  protected
  $searchFormEntries,
  $columnConfig,
  $doctrineRelationCache,
  $doctrineRelationCacheForeign,
  $form,
  $baseModelName,
  $columnConfigCollectionCache;
  
  /**
   * Returns a new instance of this class.
   * Expects an array of search form entries and the
   * base model for the current search.
   * 
   * @param $searchFormEntries An array of search form entries
   * @param $baseModelName The base model name ('UllUser')
   * @return ullSearchGenerator the new instance
   */
  public function __construct(array $searchFormEntries, $baseModelName)
  {
    $this->doctrineRelationCache = array();
    $this->doctrineRelationCacheForeign = array();
    $this->columnConfigCollectionCache = array();
    $this->baseModelName = $baseModelName;
    $this->searchFormEntries = $searchFormEntries;
    $this->buildColumnsConfig();
  }
  
  /**
   * Internal function which uses the helpers below to
   * handle column configuration of a search form.
   * Must be called before buildForm().
   */
  protected function buildColumnsConfig()
  {
    $this->resolveSearchFormEntriesRelations();
      
    $columns = $this->retrieveColumnDefinitions();

    foreach ($columns as $column) //doctrine column
    {
      $modelName = $column['searchFormEntry']->modelName;
      
      if (!isset($this->doctrineRelationCache[$modelName]))
      {
        $this->doctrineRelationCache[$modelName] = ullGeneratorHelper::resolveDoctrineRelations($modelName);
        $this->doctrineRelationCacheForeign[$modelName] = ullGeneratorHelper::resolveDoctrineRelationsForeign($modelName);
      }

      $columnRelations = $this->doctrineRelationCache[$modelName];
      $columnRelationsForeign = $this->doctrineRelationCacheForeign[$modelName];
      $columnConfig = $this->buildSingleColumnConfig($column, $columnRelations, $columnRelationsForeign);

      $this->columnConfig[$columnConfig->getCustomAttribute('searchFormEntry')->__toString()] = $columnConfig;
    }
  }
  
  /**
   * Internal function which resolves the final model name for each
   * search form entry according to the given relations.
   * Dynamically sets their->modelName for further use later on.
   */
  protected function resolveSearchFormEntriesRelations()
  {
    //resolve relations in the search form entries
    foreach ($this->searchFormEntries as $searchFormEntry)
    {
      $tempClassName = $this->baseModelName;
      if (count($searchFormEntry->relations) > 0)
      {
        for ($i = 0; $i < count($searchFormEntry->relations); $i++)
        {
          $relationName = $searchFormEntry->relations[$i];
          $relation = Doctrine::getTable($tempClassName)->getRelation($relationName, false);
          $tempClassName = $relation->getClass();
        }

        //var_dump("real model for this relation is: " . $tempClassName);
      }
      //we assign this on the fly
      $searchFormEntry->modelName = $tempClassName;
    
	    //|\     /|(  ___  )(  ____ \| \    /\
	    //| )   ( || (   ) || (    \/|  \  / /
	    //| (___) || (___) || |      |  (_/ / 
	    //|  ___  ||  ___  || |      |   _ (  
	    //| (   ) || (   ) || |      |  ( \ \ 
	    //| )   ( || )   ( || (____/\|  /  \ \
	    //|/     \||/     \|(_______/|_/    \/
      //This hack results in 'correct' column configuration for models
      //where UllEntity is referenced but UllUser needed.
      //Refactoring of column configuration handling should resolve this.
      if ($searchFormEntry->modelName == 'UllEntity')
      {
        $searchFormEntry->modelName = 'UllUser';
      }
    }
  }
  
  /**
   * Internal function which retrieves a single doctrine
   * column definition for a given search form entry.
   * 
   * @param $searchFormEntry
   * @return array with the column definition
   */
  protected function retrieveSingleColumnDefinition($searchFormEntry)
  {
    $columnDefinition = Doctrine::getTable($searchFormEntry->modelName)
    ->getColumnDefinition($searchFormEntry->columnName);

    return $columnDefinition;
  }
  
  /**
   * Internal function which retrieves column definition
   * for each search form entry if it's not a virtual column.
   * 
   * @return array with the column definitions
   */
  protected function retrieveColumnDefinitions()
  {
    $columns = array();

    foreach($this->searchFormEntries as $searchFormEntry)
    {
      if ($searchFormEntry->isVirtual)
      {
        $columns[$searchFormEntry->__toString()] = array('type' => 'virtual');
      }
      else
      {
        $columns[$searchFormEntry->__toString()] = $this->retrieveSingleColumnDefinition($searchFormEntry);
      }

      $columns[$searchFormEntry->__toString()]['searchFormEntry'] = $searchFormEntry;
    }

    return $columns;
  }
  
  /**
   * This function allows overriding classes to apply
   * specifiy column configuration, like virtual column
   * support. See the ullFlowSearchGenerator class for
   * a reference implementation.
   * 
   * @param $columnConfig The column configuration
   * @return The modified column configuration
   */
  protected function customColumnConfig($columnConfig)
  {
    //override this for virtual column support
    return $columnConfig;
  }
  
  /**
   * Internal function which builds a single column configuration.
   * Has similarities to the way this is done in the standard
   * generator, but also does relationship name humanization.
   * 
   * @param $column A single doctrine column
   * @param $columnRelations All doctrine column relations
   * @return UllColumnConfiguration the configured column
   */
  protected function buildSingleColumnConfig($column, $columnRelations, $columnRelationsForeign)
  {
    $realModelName = $column['searchFormEntry']->modelName;
    $columnName = $column['searchFormEntry']->columnName;
    
    if (!(isset($this->columnConfigCollectionCache[$realModelName])))
    {
      $this->columnConfigCollectionCache[$realModelName] = ullColumnConfigCollection::buildFor(
        $realModelName, $this->defaultAccess, $this->requestAction);
    }
    
    $columnConfigCollection = $this->columnConfigCollectionCache[$realModelName];
    
    //we can't always retrieve a valid column configuration here
    //e.g. for virtual columns
    if (isset($columnConfigCollection[$columnName]))
    {
      $columnConfig = $columnConfigCollection[$columnName];
    }
    else
    {
      $columnConfig = new ullColumnConfiguration($columnName, $this->defaultAccess);
    }
    
    //specific search code
    $columnConfig->setCustomAttribute('searchFormEntry', $column['searchFormEntry']);
    $columnConfig->setAccess('s');
    $columnConfig = $this->customColumnConfig($columnConfig);
    $columnConfig->setValidatorOption('required', false);
    
    switch ($columnConfig->getMetaWidgetClassName())
    {
      case 'ullMetaWidgetInteger': //fall through
      case 'ullMetaWidgetDateTime':
      case 'ullMetaWidgetDate':
        $columnConfig->setWidgetAttribute('size', 12); //hardcode this here? bad? =)
        //ToDo: the widget knows this now, remove this fragment
        break;
    }

    //if this columnConfig has relations, humanize their labels
    if (count($column['searchFormEntry']->relations) > 0)
    {
      $newLabel = '';
      foreach($column['searchFormEntry']->relations as $relation)
      {
        $newLabel .= ullHumanizer::humanizeAndTranslateRelation($relation) . " - ";
      }
      $columnConfig->setLabel($newLabel . $columnConfig->getLabel());
    }
    
    return $columnConfig;
  }

  /**
   * This function removes all search form entries from this
   * generator not contained in the given array.
   * 
   * @param $searchFormEntries An array of search form entries
   */
  public function reduce(array $searchFormEntries) {

    foreach ($this->columnConfig as $sfeKey => $columnConfig)
    {
      foreach ($searchFormEntries as $sfe)
      {
        if ($sfe->__toString() == $sfeKey) {
          $this->columnConfig[$sfeKey]->setCustomAttribute('searchFormEntry', $sfe);
          continue 2;
        }
      }
      unset($this->columnConfig[$sfeKey]);
    }
  }

  /**
   * Builds the search form. Similar to the buildForm of standard
   * generators, however this also does range field support.
   */
  public function buildForm()
  {
    $this->form = new sfForm();
    
    foreach ($this->columnConfig as $columnName => $columnConfig)
    {
      for ($i = 0; $i < $columnConfig->getCustomAttribute('searchFormEntry')->multipleCount; $i++)
      {
        $ullMetaWidgetClassName = $columnConfig->getMetaWidgetClassName();
        $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig, $this->form);

        $searchPrefix = $ullMetaWidget->getSearchType();
        $enumeratedColumnName = $i . '_' . $columnConfig->getCustomAttribute('searchFormEntry')->uuid;
        switch ($searchPrefix)
        {
          case 'range':
            $ullMetaWidget->addToFormAs('rangeFrom_' . $enumeratedColumnName);
            $this->form->getWidgetSchema()->setLabel('rangeFrom_' . $enumeratedColumnName, __($columnConfig->getLabel(), null, 'common'));

            $ullMetaWidget->addToFormAs('rangeTo_' . $enumeratedColumnName);
            $this->form->getWidgetSchema()->setLabel('rangeTo_' . $enumeratedColumnName, __('to', null, 'common') . ':');
            break;

          case 'foreign':
          case 'boolean':
          case 'standard':
            $ullMetaWidget->addToFormAs($searchPrefix . '_' . $enumeratedColumnName);
            $this->form->getWidgetSchema()->setLabel($searchPrefix . '_' . $enumeratedColumnName, __($columnConfig->getLabel(), null, 'common'));
            break;

          default:
            throw new RuntimeException("Invalid search prefix received from widget.");
        }
      }
    }
  }

  /**
   * Returns a humanized label for a qualified column name.
   * 
   * @param $qualifiedColumnName The qualified column name
   * @return The label
   */
  public function getColumnLabel($qualifiedColumnName)
  {
    if (isset($this->columnConfig[$qualifiedColumnName]) && ($this->columnConfig[$qualifiedColumnName]->getLabel() != null))
    {
      return $this->columnConfig[$qualifiedColumnName]->getLabel();
    }
     
    return null;
  }
  
  /**
   * Returns the search form entries of this search.
   * 
   * @return array The search form entries
   */
  public function getSearchFormEntries()
  {
    return $this->searchFormEntries;
  }

  /**
   * Returns the form for this search.
   * 
   * @return sfForm The form
   */
  public function getForm()
  {
    return $this->form;
  }
}