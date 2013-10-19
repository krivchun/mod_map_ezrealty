<?php

/**
 * @package Map Ezrealty Module for Joomla 1.7-2.5
 * @version 1.0.0
 * @author Igor Krivchun
 * @copyright (C) 2013- Igor Krivchun
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//init Joomla framework
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', str_replace( DS.'modules'.DS.'mod_map_ezrealty', '', realpath(dirname(__FILE__))) );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

JFactory::getApplication('site')->initialise();

//include helpers of the component and module
require_once ( JPATH_SITE . DS . 'components' . DS .'com_ezrealty' . DS .'helpers' . DS . 'route.php' );
require_once ( dirname(__FILE__) . DS . "helper.php" );
modMapEzrealtyHelper::initialize();

$countryId = JRequest::getCmd('country');

if( is_numeric($countryId) )
    echo(json_encode( modMapEzrealtyHelper::getPreparedObjectsList(array($countryId)) ));

exit();

?>