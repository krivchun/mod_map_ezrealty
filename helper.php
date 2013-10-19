<?php

// no direct access
defined('_JEXEC') or die;

class modMapEzrealtyHelper {
    private static $initialized = false;
    private static $ezrparams;
    private static $db;

    public static function initialize() {
        if( self::$initialized ) return;
        
        $db =& JFactory::getDBO();
        $ezrparams = JComponentHelper::getParams ('com_ezrealty');

        self::$ezrparams = $ezrparams;
        self::$db = $db;

        self::$initialized = true;
    }

    public static function getEzParams() {
        if( !self::$initialized ) return false;
        
        return self::$ezrparams;
    }
    
    public static function getDb() {
        if( !self::$initialized ) return false;
        
        return self::$db;
    }
    
    public static function getCountryList() {
        if( !self::$initialized ) return false;
        
        $orderby = self::$ezrparams->get('deflistorder') == 1 ? 't.name' : 't.ordering';
        $query = "SELECT t.id AS value, t.name AS text FROM #__ezrealty_country AS t WHERE t.published = 1 ORDER BY $orderby ";
        self::$db->setQuery( $query );
        return self::$db->loadObjectList();
    }
    
    public static function getCountryIds() {
        $countryList = self::getCountryList();
        $countryIds = array();
        foreach($countryList as $country) {
            $countryIds[] = (int)$country->value;
        }
        return $countryIds;
    }
    
    public static function getObjectsList($countryIds) {
        if( !self::$initialized ) return false;
        
        $query = ' SELECT a.id AS id, a.cnid AS cnid, a.declat AS lat, a.declong AS lon,'

        .' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug, '

        .' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END as catslug '

        .' FROM #__ezrealty AS a'

        .' LEFT JOIN #__ezrealty_catg AS cc ON cc.id = a.cid'

        .' WHERE a.published = 1 AND cc.published=1 AND a.cnid IN ('.implode(', ', $countryIds).')';
        
        self::$db->setQuery( $query );        
        $objects = self::$db->loadObjectList();
        
        $result = array();
        foreach($countryIds as $countryId) {
            $result[(int)$countryId] = array();
        }
        
        foreach($objects as $object) {
            $result[(int)$object->cnid][] = $object;
        }
        
        return $result;
    }
    
    public static function getPreparedObjectsList($countryIds) {
        
        $countries = self::getObjectsList($countryIds);
        
        $results = array();
        foreach ($countries as $country_id => $country) {
            $results[(string)$country_id] = array();
            foreach ($country as $object) {                
                $prepared = new stdClass();
                $prepared->url = str_replace('/modules/mod_map_ezrealty', '', JRoute::_(EzrealtyHelperRoute::getEzrealtyRoute($object->slug, $object->catslug)) ); 
                $prepared->lat = $object->lat;
                $prepared->lon = $object->lon;
                
                $results[(string)$country_id][] = $prepared;
            }
        }
        
        return $results;
    }
    
    public static function parseCountriesData($rawCountriesData) {
        $rawCountriesData = trim($rawCountriesData);
        $rawCountriesDataArray = explode(';', $rawCountriesData);

        $results = array();
        foreach($rawCountriesDataArray as $rawCountryData) {
            $rawCountryData = trim($rawCountryData);
            if( preg_match('/([0-9]+)\|([0-9]+)\|([0-9\.\-]+)\|([0-9\.\-]+)/ui', $rawCountryData, $matches) ) {
                $results[(string)$matches[1]] = array(
                    'id' => $matches[1],
                    'zoom' => $matches[2],
                    'lat' => $matches[3],
                    'lon' => $matches[4]
                );
            }
        }

        return $results;
    }
}

?>