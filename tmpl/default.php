<?php
/**
 * @package   mod_nivo_slider
 * copyright Igor Krivchun
 * @license GPL3
 */

// no direct access
defined('_JEXEC') or die;
	

$urlModule = $urlBase."modules/{$module->module}/";
$urlModuleTemplate = $urlModule."tmpl/";

$document = JFactory::getDocument();

//add css
$document->addStyleSheet($urlModuleTemplate."css/map_ezrealty.css");

//add js
$urlJqueryInclude = $urlModuleTemplate."js/jquery-1.9.1.min.js";
$urlGoogleMapsApiInclude = 'http://maps.googleapis.com/maps/api/js?sensor=false';
$urlClustererInclude = $urlModuleTemplate."js/markerclusterer_compiled.js";
$urlMapEzrealtyInclude = $urlModuleTemplate."js/map_ezrealty.js";

$include_jquery = $params->get("include_jquery","true");
$include_google_maps_api = $params->get("include_google_maps_api","true");
$include_google_maps_api_clusterer = $params->get("include_google_maps_api_clusterer","true");

$js_load_type = $params->get("js_load_type","head");		
if($js_load_type == "head") {

	if($include_jquery == "true")
		$document->addScript($urlJqueryInclude);

	if($include_google_maps_api == "true")
		$document->addScript($urlGoogleMapsApiInclude);

	if($include_google_maps_api_clusterer == "true")
		$document->addScript($urlClustererInclude);
		
        $document->addScript($urlMapEzrealtyInclude);
}

//get module id
$map_ezrealty_ID = "map_ezrealty_".$module->id;
	
?>
<!--  Begin "Map Ezrealty" -->
<?php if($js_load_type == "body" && !defined("MAP_EZREALTY_INCLUDED")): ?>
	<?php if($include_jquery == "true"): ?>
	<script type="text/javascript" src="<?php echo $urlJqueryInclude; ?>"></script>
	<?php endif ?>
	<?php if($include_google_maps_api == "true"): ?>
	<script type="text/javascript" src="<?php echo $urlGoogleMapsApiInclude; ?>"></script>
	<?php endif ?>
	<?php if($include_google_maps_api_clusterer == "true"): ?>
	<script type="text/javascript" src="<?php echo $urlClustererInclude; ?>"></script>
	<?php endif ?>
	<script type="text/javascript" src="<?php echo $urlMapEzrealtyInclude; ?>"></script>
<?php endif; ?>
<script type="text/javascript">
<?php if($params->get("no_conflict_mode") == "true"): ?>
    jQuery.noConflict();
<?php endif; ?>
    jQuery(document).ready(function() {
            var moduleID = '<?php echo $map_ezrealty_ID; ?>',
                startCountryId = <?php echo reset(modMapEzrealtyHelper::getCountryList())->value; ?>,
                //startCountriesData = <?php echo json_encode(modMapEzrealtyHelper::getPreparedObjectsList( array(reset(modMapEzrealtyHelper::getCountryList())->value) )); ?>,
                startCountriesData = <?php echo json_encode(modMapEzrealtyHelper::getPreparedObjectsList( modMapEzrealtyHelper::getCountryIds() )); ?>,
                countriesData = <?php echo( json_encode(modMapEzrealtyHelper::parseCountriesData($params->get("countries_data",""))) ); ?>,
                moduleUrl = '<?php echo($urlModule); ?>',
                clusterSize = <?php echo($params->get("cluster_size","30")); ?>;
            initMapEzrealty(moduleID, moduleUrl, countriesData, startCountryId, startCountriesData, clusterSize);
    });	//ready
</script>
<div class="map_ezrealty_wrapper">
	<div class="map_ezrealty_coutry_box">
            <h3><?php echo $params->get("header_text","Map Ezrealty Module"); ?></h3>
            <ul id="<?php echo $map_ezrealty_ID; ?>_coutry_list" class="map_ezrealty_coutry_list">
                <?php foreach(modMapEzrealtyHelper::getCountryList() as $country) { ?>
                <li><a data-id="<?php echo($country->value); ?>" href="javascript:void(0);"><?php echo($country->text); ?></a></li>
                <?php } ?>
            </ul>
	</div>
	<div id="<?php echo $map_ezrealty_ID; ?>_map_box" class="map_ezrealty_gmap_box"></div>
</div>
<!--  End "Map Ezrealty" -->
<?php
	if($js_load_type == "body")
		define("MAP_EZREALTY_INCLUDED",true);
?>