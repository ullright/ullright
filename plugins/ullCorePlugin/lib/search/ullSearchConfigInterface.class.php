<?php

/**
 * This interface is used by ullSearch configurations.
 * It forces implementation classes to provide enumerations
 * of default and all search fields.
 */
interface ullSearchConfig {
  public function getDefaultSearchColumns();
  public function getAllSearchableColumns();
}
