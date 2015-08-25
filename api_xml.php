<?php
// =====================================================================
// api_xml.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownMaps.org/
// ---------------------------------------------------------------------
// API : XML incl. KML
// =====================================================================

class ownmaps_xml
{

	var $request;
	var $response;
	var $decode;

	var $boundsswlat;
	var $boundsswlon;
	var $boundsnelat;
	var $boundsnelon;

	function ownmaps_xml()
	{
		$this->Reset();
	}

	function Reset()
	{
		$this->request  = "";
		$this->response = "";
		$this->decode   = "";

		$this->boundsswlat =  999;
		$this->boundsswlon =  999;
		$this->boundsnelat = -999;
		$this->boundsnelon = -999;
	}

	function setBOUNDS( $LAT, $LON )
	{
		if ( $this->boundsswlat > $LAT ) $this->boundsswlat = $LAT;
		if ( $this->boundsswlon > $LON ) $this->boundsswlon = $LON;
		if ( $this->boundsnelat < $LAT ) $this->boundsnelat = $LAT;
		if ( $this->boundsnelon < $LON ) $this->boundsnelon = $LON;
	}

	function getXML( $URL )
	{
//		$this->Reset(); $this->request = $URL;

//		$this->response = file_get_contents( $this->request );

//		$this->decode = json_decode( $this->response );
	}

	function getKML( $URL )
	{
//		$this->getXML( $URL );
	}

}

// =====================================================================
?>