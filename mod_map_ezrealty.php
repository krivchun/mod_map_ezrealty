<?php

/**
 * @package Map Ezrealty Module for Joomla 1.7-2.5
 * @version 1.0.0
 * @author Igor Krivchun
 * @copyright (C) 2013- Igor Krivchun
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die;

//set active menu link
$urlBase = JURI::base();

//include helpers of the component and module
require_once ( JPATH_SITE . DS . 'components' . DS .'com_ezrealty' . DS .'helpers' . DS . 'route.php' );
require_once ( dirname(__FILE__) . DS . "helper.php" );
modMapEzrealtyHelper::initialize();

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));

	

	