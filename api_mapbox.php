<?php
// =====================================================================
// api_mapbox.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownMaps.org/
// ---------------------------------------------------------------------
// API : Mapbox
// =====================================================================

class ownmaps_mapbox
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

	function ownmaps_mapbox()
	{
		$this->Reset();
		$this->loadAPI();
	}

	function Reset()
	{
		global $_ownmaps_mapbox_token, $_ownmaps_mapbox_map;

		$this->map      = "";
		$this->mapid    = "ommb";
		$this->mapcount = 0;

		$this->tiles    = $_ownmaps_mapbox_map;
		$this->tileskey = $_ownmaps_mapbox_token;

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
		$tmp .= "https://api.mapbox.com/mapbox.js/v2.2.1/mapbox.css";
		$tmp .= "\" />";

		$tmp  = "<script src=\"";
		$tmp .= "https://api.mapbox.com/mapbox.js/v2.2.1/mapbox.js";
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

		$tmp .= "var map_".$this->map." = L.mapbox.map( '".$this->map."', '".$this->tiles."', { ";
//		$tmp .= "center: [".$LAT.",".$LON."], ";
//		$tmp .= "zoom: ".$ZOOM.", ";

		$tmp .= "accessToken: '".$this->tileskey."', ";


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

		$tmp  = "var geojson = ".$OMjson->response.";";

		$tmp .= "var geojsonLayer = L.geoJson( geojson, { style: { fillColor: 'gray', color: 'black', weight: 1 } } ).addTo( map_".$this->map." );";



		print $tmp;
	}

	function addKML( $URL="" )
	{
		$tmp  = "var kmlLayer = omnivore.kml( '".$URL."' ).addTo( map_".$this->map." );";

		print $tmp;
	}

	function addLAYERS()
	{
	}

	function getEVENT()
	{
	}

}

// =====================================================================
?>