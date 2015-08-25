<?php
// =====================================================================
// api_json.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownMaps.org/
// ---------------------------------------------------------------------
// API : JSON incl. GeoJSON
// =====================================================================

class ownmaps_json
{

	var $request;
	var $response;
	var $decode;

	var $boundsswlat;
	var $boundsswlon;
	var $boundsnelat;
	var $boundsnelon;

	function ownmaps_json()
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

	function getJSON( $URL )
	{
		$this->Reset(); $this->request = $URL;

		$this->response = file_get_contents( $this->request );

		$this->decode = json_decode( $this->response );
	}

	function getGEOJSON( $URL )
	{
		$this->getJSON( $URL );

		if ( !empty($this->decode) ) {
			foreach( $this->decode->features as $f ) {
				if ( $f->geometry->type=="Point" ) {
					$lonlat = $f->geometry->coordinates; $LON = $lonlat[0]; $LAT = $lonlat[1]; $this->setBOUNDS( $LAT, $LON );
				} elseif ( $f->geometry->type=="LineString" OR $f->geometry->type=="MultiPoint" ) {
					foreach( $f->geometry->coordinates as $lonlat ) { $LON = $lonlat[0]; $LAT = $lonlat[1]; $this->setBOUNDS( $LAT, $LON ); }
				} elseif ( $f->geometry->type=="Polygon" OR $f->geometry->type=="MultiLineString" ) {
					foreach( $f->geometry->coordinates as $c ) {
						foreach( $c as $lonlat ) { $LON = $lonlat[0]; $LAT = $lonlat[1]; $this->setBOUNDS( $LAT, $LON ); }
					}
				} elseif ( $f->geometry->type=="MultiPolygon" ) {
					foreach( $f->geometry->coordinates as $c ) {
						foreach( $c as $m ) {
							foreach( $m as $lonlat ) { $LON = $lonlat[0]; $LAT = $lonlat[1]; $this->setBOUNDS( $LAT, $LON ); }
						}
					}
				} elseif ( $f->geometry->type=="GeometryCollection" ) {
					foreach( $f->geometry->geometries as $g ) {
						if ( $g->type=="Point" ) {
							$lonlat = $g->coordinates; $LON = $lonlat[0]; $LAT = $lonlat[1]; $this->setBOUNDS( $LAT, $LON );
						} elseif ( $g->type=="LineString" OR $g->type=="MultiPoint" ) {
							foreach( $g->coordinates as $lonlat ) { $LON = $lonlat[0]; $LAT = $lonlat[1]; $this->setBOUNDS( $LAT, $LON ); }
						} elseif ( $g->type=="Polygon" OR $g->type=="MultiLineString" ) {
							foreach( $g->coordinates as $gc ) {
								foreach( $gc as $lonlat ) { $LON = $lonlat[0]; $LAT = $lonlat[1]; $this->setBOUNDS( $LAT, $LON ); }
							}
						} elseif ( $g->type=="MultiPolygon" ) {
							foreach( $g->coordinates as $gc ) {
								foreach( $gc as $gm ) {
									foreach( $gm as $lonlat ) { $LON = $lonlat[0]; $LAT = $lonlat[1]; $this->setBOUNDS( $LAT, $LON ); }
								}
							}
						}
					}
				}
			}
		}
	}

}

// =====================================================================
?>