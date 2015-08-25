<?php
// =====================================================================
// api_leaflet.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownMaps.org/
// ---------------------------------------------------------------------
// API : Leaflet
// =====================================================================

class ownmaps_leaflet
{

	var $map;
	var $mapid;
	var $mapcount;
	var $tiles;
	var $tileskey;
	var $centerlat;
	var $centerlon;
	var $centerzoom;
	var $boundsswlat;
	var $boundsswlon;
	var $boundsnelat;
	var $boundsnelon;
	var $countid;

	function ownmaps_leaflet()
	{
		$this->Reset();
		$this->loadAPI();
	}

	function Reset()
	{


		$this->map      = "";
		$this->mapid    = "omll";
		$this->mapcount = 0;

		$this->tiles    = "'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'";
		$this->tileskey = "";

		$this->centerlat   =    0;
		$this->centerlon   =    0;
		$this->centerzoom  =    0;

		$this->boundsswlat =  999;
		$this->boundsswlon =  999;
		$this->boundsnelat = -999;
		$this->boundsnelon = -999;

		$this->countid = 0;
	}

	function setBOUNDS( $LAT, $LON )
	{
		if ( $this->boundsswlat > $LAT ) $this->boundsswlat = $LAT;
		if ( $this->boundsswlon > $LON ) $this->boundsswlon = $LON;
		if ( $this->boundsnelat < $LAT ) $this->boundsnelat = $LAT;
		if ( $this->boundsnelon < $LON ) $this->boundsnelon = $LON;
	}

	function getANCHOR( $ICON )
	{
		$tmp = getimagesize( $ICON ); $tmpx = $tmp[0]/2; $tmpy = $tmp[1];

		return ", iconSize: [".$tmp[0].",".$tmp[1]."], iconAnchor: [".$tmpx.",".$tmpy."]";
	}

	function loadAPI()
	{
		$tmp  = "<link rel=\"stylesheet\" href=\"";
		$tmp .= "http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css";
		$tmp .= "\" />";

		$tmp .= "<script src=\"";
		$tmp .= "http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js";
		$tmp .= "\"></script>";

		$tmp .= "<script src=\"";
		$tmp .= "https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.2.0/leaflet-omnivore.min.js";
		$tmp .= "\"></script>";

		print $tmp;
	}

	function createMAP()
	{
		$this->mapcount++; $this->map = $this->mapid.$this->mapcount;

		$tmp  = "<div id=\"".$this->map."\" ";
		$tmp .= "style=\"width: 100%; height: 100%; margin: 0px; padding: 0px\">";
		$tmp .= "</div>";

		$tmp .= "<script>";

		$tmp .= "var map_".$this->map." = L.map( '".$this->map."', { ";
//		$tmp .= "center: [".$LAT.",".$LON."], ";
//		$tmp .= "zoom: ".$ZOOM.", ";
		$tmp .= "layers: L.tileLayer( ".$this->tiles." ), ";



		$tmp .= "zoomControl: true";
		$tmp .= " } ).setView( [".$this->centerlat.",".$this->centerlon."], ".$this->centerzoom." );";

		print $tmp;
	}

	function initMAP()
	{
		$tmp  = "";

		if ( $this->centerzoom == 0 ) {
			$tmp .= "map_".$this->map.".fitBounds( [ ";
			$tmp .= "[".$this->boundsswlat.",".$this->boundsswlon."], ";
			$tmp .= "[".$this->boundsnelat.",".$this->boundsnelon."]";
			$tmp .= " ] );";
		}

		$tmp .= "</script>";

		print $tmp;
	}

	function addMARKER( $LAT=0, $LON=0, $TITLE="", $INFO="", $ICON="", $RAD=3, $COL="black" )
	{
		$this->countid++; $this->setBOUNDS( $LAT, $LON );

		$tmp  = "var m_".$this->mapid.$this->countid." = L.marker( [".$LAT.",".$LON."], { ";


		if ( $ICON != "" ) {
			if ( $ICON == "__CIRC__" ) {

			} else {
				$tmp .= "icon: L.icon( { iconUrl: '".$ICON."'".$this->getANCHOR( $ICON )." } ), ";
			}
		}
		$tmp .= "title: '".$TITLE."'";
		$tmp .= " } ).addTo( map_".$this->map." );";

		if ( $INFO != "" ) {
			if ( strtolower( substr( $INFO, 0, 4 ) ) == "http" ) {
				$tmp .= "m_".$this->mapid.$this->countid.".on( 'click', function() { ";
				$tmp .= "window.open( '".$INFO."' );";
				$tmp .= " } );";
			} else {



				$tmp .= "m_".$this->mapid.$this->countid.".bindPopup( ";
				$tmp .= "'".$INFO."'";
				$tmp .= " );";
			}
		}

		print $tmp;
	}

	function addGEOJSON( $URL="" )
	{
		global $OMjson;

		$OMjson->getGEOJSON( $URL ); $this->setBOUNDS( $OMjson->boundsswlat, $OMjson->boundsswlon ); $this->setBOUNDS( $OMjson->boundsnelat, $OMjson->boundsnelon );

		$tmp  = "var geojsonFeature = ".$OMjson->response.";";

		$tmp .= "var geojsonLayer = L.geoJson( geojsonFeature ).addTo( map_".$this->map." );";

		print $tmp;
	}

	function addKML( $URL="" )
	{
		$tmp  = "var kmlLayer = omnivore.kml( '".$URL."' ).addTo( map_".$this->map." );";

		print $tmp;
	}

	function addLAYERS()
	{
		$osm  = "&copy; <a href=\"http://openstreetmap.org/copyright\">OpenStreetMap</a> contributors";

		$tmp = "L.control.layers( { ";
		$tmp .= "'OSM':                L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',                       { attribution: '".$osm."' } ), ";
		$tmp .= "'Mapnik BW':          L.tileLayer( 'http://{s}.www.toolserver.org/tiles/bw-mapnik/{z}/{x}/{y}.png',           { attribution: '".$osm."' } ), ";
		$tmp .= "'OSM DE':             L.tileLayer( 'http://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png',            { attribution: '".$osm."' } ), ";
		$tmp .= "'OSM HOT':            L.tileLayer( 'http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',                    { attribution: '".$osm.", Tiles courtesy of <a href=\"http://hot.openstreetmap.org/\" target=\"_blank\">Humanitarian OpenStreetMap Team</a>' } ), ";
		$tmp .= "'OpenSeaMap':         L.tileLayer( 'http://tiles.openseamap.org/seamark/{z}/{x}/{y}.png',                     { attribution: '".$osm.", Map data &copy; <a href=\"http://www.openseamap.org\">OpenSeaMap</a> contributors' } ), ";
		$tmp .= "'OpenMapSurfer':      L.tileLayer( 'http://openmapsurfer.uni-hd.de/tiles/roads/x={x}&y={y}&z={z}',            { attribution: '".$osm.", Imagery from <a href=\"http://giscience.uni-hd.de/\">GIScience Research Group @ University of Heidelberg</a>' } ), ";
		$tmp .= "'OpenMapSurfer gray': L.tileLayer( 'http://openmapsurfer.uni-hd.de/tiles/roadsg/x={x}&y={y}&z={z}',           { attribution: '".$osm.", Imagery from <a href=\"http://giscience.uni-hd.de/\">GIScience Research Group @ University of Heidelberg</a>' } ), ";
		$tmp .= "'Mapbox':             L.tileLayer( 'http://{s}.tiles.mapbox.com/v3/landplanner.map-xswoybbb/{z}/{x}/{y}.png', { attribution: '".$osm.", Imagery from <a href=\"http://mapbox.com/about/maps/\">Mapbox</a>' } )";
		$tmp .= " } ).addTo( map_".$this->map." );";

		print $tmp;
	}

	function getEVENT()
	{
	}

}

// =====================================================================
?>