<?php
// =====================================================================
// api_panoramio.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownmaps.org/
// ---------------------------------------------------------------------
// API : Panoramio
// ---------------------------------------------------------------------

class ownmaps_panoramio
{

	var $uid;
	var $from, $to, $count;
	var $lat, $lon, $rad, $latmin, $lonmin, $latmax, $lonmax;

	var $REQUEST, $RESPONSE, $JSONDEC, $DATA;

	function ownmaps_panoramio()
	{
		$this->Reset();
	}

	function Reset()
	{
		$this->setUSERID();
		$this->setFROMTO();
		$this->setBOUNDS();




	}

	function setUSERID( $UID="public" )
	{
		$this->uid = $UID;
	}

	function setFROMTO( $FROM=0, $COUNT=100 )
	{
		$this->from  = $FROM;
		$this->to    = $this->from + $COUNT;
		$this->count = $COUNT;
	}

	function setBOUNDS( $LATMIN=-90, $LONMIN=-180, $LATMAX=90, $LONMAX=180 )
	{
		$this->latmin = $LATMIN;
		$this->lonmin = $LONMIN;
		$this->latmax = $LATMAX;
		$this->lonmax = $LONMAX;

		$this->lat = ( $LATMAX + $LATMIN )/2;
		$this->lon = ( $LONMAX + $LONMIN )/2;
		$this->rad = 1;
	}

	function calcBOUNDS( $LAT=0.0, $LON=0.0, $RAD=1.0 )
	{
		$this->lat = $LAT;
		$this->lon = $LON;
		$this->rad = $RAD;

		$earthRadius = 6371;

		$directionBearing = 225;

		$this->latmin = rad2deg(asin(sin(deg2rad($LAT)) * cos($RAD / $earthRadius) + cos(deg2rad($LAT)) * sin($RAD / $earthRadius) * cos(deg2rad($directionBearing))));
		$this->lonmin = rad2deg(deg2rad($LON) + atan2(sin(deg2rad($directionBearing)) * sin($RAD / $earthRadius) * cos(deg2rad($LAT)), cos($RAD / $earthRadius) - sin(deg2rad($LAT)) * sin(deg2rad($this->latmin))));

		$directionBearing = 45;

		$this->latmax = rad2deg(asin(sin(deg2rad($LAT)) * cos($RAD / $earthRadius) + cos(deg2rad($LAT)) * sin($RAD / $earthRadius) * cos(deg2rad($directionBearing))));
		$this->lonmax = rad2deg(deg2rad($LON) + atan2(sin(deg2rad($directionBearing)) * sin($RAD / $earthRadius) * cos(deg2rad($LAT)), cos($RAD / $earthRadius) - sin(deg2rad($LAT)) * sin(deg2rad($this->latmax))));
	}

	function setREQUEST()
	{
		$this->REQUEST  = 'http://www.panoramio.com/map/get_panoramas.php';

		$this->REQUEST .= '?set='. $this->uid;
		$this->REQUEST .= '&from='.$this->from;
		$this->REQUEST .= '&to='.  $this->to;
		$this->REQUEST .= '&minx='.$this->lonmin;
		$this->REQUEST .= '&miny='.$this->latmin;
		$this->REQUEST .= '&maxx='.$this->lonmax;
		$this->REQUEST .= '&maxy='.$this->latmax;
		$this->REQUEST .= '&size=small';
//		$this->REQUEST .= '&mapfilter=true';
	}

	function getRESPONSE()
	{
		$this->RESPONSE = file_get_contents( $this->REQUEST );

		$this->JSONDEC = json_decode( $this->RESPONSE );

		$this->DATA = $this->JSONDEC->photos;
	}

	function addMARKER( $OMapi, $OMico, $LAT, $LON, $RAD=0.141, $CNT=10, $UID="public" )
	{

		global $_ownmaps_app;

		$this->Reset();
		$this->setUSERID( $UID );
		$this->setFROMTO( 0, $CNT );
		$this->calcBOUNDS( $LAT, $LON, $RAD );
		$this->setREQUEST();
		$this->getRESPONSE();

		if ( !empty($this->DATA) ) {
//			$OMapi->addMARKER( $this->latmin, $this->lonmin, "Panoramio", "", "_pin" );
//			$OMapi->addMARKER( $this->latmax, $this->lonmax, "Panoramio", "", "_pin" );
//			$OMapi->addMARKER( $this->latmin, $this->lonmax, "Panoramio", "", "_pin" );
//			$OMapi->addMARKER( $this->latmax, $this->lonmin, "Panoramio", "", "_pin" );
			foreach( $this->DATA as $d ) {
				$info_logo  = "<a href=\"http://panoramio.com\" target=\"_blank\"><img src=\"".$_ownmaps_app."logo_panoramio.png\"></a>";
				$info_image = "<a href=\"".$d->photo_url."\" target=\"_blank\"><img src=\"".$d->photo_file_url."\"></a>";
				$info_owner = "<a href=\"".$d->owner_url."\" target=\"_blank\">".$d->owner_name."</a>";
				$info       = $info_logo."<br>".$info_image."<br><small>Author: </small>".$info_owner;
				$OMapi->addMARKER( $d->latitude, $d->longitude, "Panoramio", $info, $OMico );
			}
		}
	}

}

// =====================================================================
?>