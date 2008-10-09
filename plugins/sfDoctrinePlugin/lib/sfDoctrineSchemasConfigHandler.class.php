<?php
/*
 * This file is part of the sfDoctrinePlugin package.
 * (c) 2006-2007 Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrineSchemasConfigHandler parses the config/schemas.yml config file
 *
 * @package    sfDoctrinePlugin
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineSchemasConfigHandler.class.php 8743 2008-05-03 05:02:39Z Jonathan.Wage $
 */
class sfDoctrineSchemasConfigHandler extends sfYamlConfigHandler
{
  /**
   * execute
   *
   * @param string $configFiles
   * @return void
   */
  public function execute($configFiles)
  {
    $this->initialize();

    $mappings = $this->parseYamls( $configFiles );

    $data = array();

    $data[] = '$manager = Doctrine_Manager::getInstance();'."\n";

    foreach ( $mappings as $mapping => $schemas )
    {
      foreach ( $schemas as $schema )
      {
        $path = sfConfig::get( 'sf_config_dir' ) . '/doctrine/' . $schema . '.yml';
        $components = array_keys( sfYaml::load( $path ) );

        foreach ( $components as $component )
        {
          $data[] = "\$manager->bindComponent('{$component}', '{$mapping}');";
        }
      }
    }

    // compile data
    $retval = sprintf("<?php\n".
                      "// auto-generated by sfDoctrineSchemasConfigHandler\n".
                      "// date: %s\n%s\n",
                      date('Y-m-d H:i:s'), implode("\n", $data));

    return $retval;
  }
}