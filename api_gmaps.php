<?php
// =====================================================================
// api_gmaps.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownMaps.org/
// ---------------------------------------------------------------------
// API : Google Maps
// =====================================================================

class ownmaps_gmaps
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

	function ownmaps_gmaps()
	{
		$this->Reset();
		$this->loadAPI();
	}

	function Reset()
	{


		$this->map      = "";
		$this->mapid    = "omgm";
		$this->mapcount = 0;

		$this->tiles    = "ROADMAP";
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

		return ", anchor: new google.maps.Point(".$tmpx.",".$tmpy.")";
	}

	function loadAPI()
	{




		$tmp  = "<script src=\"";
		$tmp .= "https://maps.googleapis.com/maps/api/js?v=3.exp";
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

		$tmp .= "var map_".$this->map." = new google.maps.Map( document.getElementById( '".$this->map."' ), { ";
		$tmp .= "center: new google.maps.LatLng(".$this->centerlat.",".$this->centerlon."), ";
		$tmp .= "zoom: ".$this->centerzoom.", ";
		$tmp .= "mapTypeId: google.maps.MapTypeId.".$this->tiles.", ";
		$tmp .= "panControl: false, ";
//		$tmp .= "scaleControl: true, ";
//		$tmp .= "overviewMapControl: true, ";
		$tmp .= "zoomControl: true";
		$tmp .= " } );";

		print $tmp;
	}

	function initMAP()
	{
		$tmp  = "";

		if ( $this->centerzoom == 0 ) {
			$tmp .= "map_".$this->map.".fitBounds( new google.maps.LatLngBounds( ";
			$tmp .= "new google.maps.LatLng(".$this->boundsswlat.",".$this->boundsswlon."), ";
			$tmp .= "new google.maps.LatLng(".$this->boundsnelat.",".$this->boundsnelon.")";
			$tmp .= " ) );";
		}

		$tmp .= "</script>";

		print $tmp;
	}

	function addMARKER( $LAT=0, $LON=0, $TITLE="", $INFO="", $ICON="", $RAD=3, $COL="black" )
	{
		$this->countid++; $this->setBOUNDS( $LAT, $LON );

		$tmp  = "var m_".$this->mapid.$this->countid." = new google.maps.Marker( { ";
		$tmp .= "map: map_".$this->map.", ";
		$tmp .= "position: new google.maps.LatLng(".$LAT.",".$LON."), ";
		if ( $ICON != "" ) {
			if ( $ICON == "__CIRC__" ) {
				$tmp .= "icon: { path: google.maps.SymbolPath.CIRCLE, scale: ".$RAD.", strokeColor: '".$COL."' }, ";
			} else {
				$tmp .= "icon: { url: '".$ICON."'".$this->getANCHOR( $ICON )." }, ";
			}
		}
		$tmp .= "title: '".$TITLE."'";
		$tmp .= " } );";

		if ( $INFO != "" ) {
			if ( strtolower( substr( $INFO, 0, 4 ) ) == "http" ) {
				$tmp .= "google.maps.event.addListener( m_".$this->mapid.$this->countid.", 'click', function() { ";
				$tmp .= "window.open( '".$INFO."' );";
				$tmp .= " } );";
			} else {
				$tmp .= "var i_".$this->mapid.$this->countid." = new google.maps.InfoWindow( { ";
				$tmp .= "content: '".$INFO."'";
				$tmp .= " } );";
				$tmp .= "google.maps.event.addListener( m_".$this->mapid.$this->countid.", 'click', function() { ";
				$tmp .= "i_".$this->mapid.$this->countid.".open( map_".$this->map.", m_".$this->mapid.$this->countid." );";
				$tmp .= " } );";
			}
		}

		print $tmp;
	}

	function addGEOJSON( $URL="" )
	{
		global $OMjson;

		$OMjson->getGEOJSON( $URL ); $this->setBOUNDS( $OMjson->boundsswlat, $OMjson->boundsswlon ); $this->setBOUNDS( $OMjson->boundsnelat, $OMjson->boundsnelon );



		$tmp  = "map_".$this->map.".data.loadGeoJson( '".$URL."' );";

		$tmp .= "map_".$this->map.".data.setStyle( { fillColor: 'gray', strokeColor: 'black', strokeWeight: 1 } );";

		print $tmp;
	}

	function addKML( $URL="" )
	{
		$tmp  = "var kmlLayer = new google.maps.KmlLayer( { map: map_".$this->map.", preserveViewport: true, url: '".$URL."' } );";

		print $tmp;
	}

	function addLAYERS()
	{
	}

	function getEVENT()
	{
		$tmp  = "google.maps.event.addListener( map_".$this->map.", 'rightclick', function() { ";
		$tmp .= "window.alert('DIV clicked');";
		$tmp .= " } );";

		print $tmp;
	}

}

// =====================================================================
?>