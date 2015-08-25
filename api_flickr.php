<?php
// =====================================================================
// api_flickr.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownmaps.org/
// ---------------------------------------------------------------------
// API : Flickr
// ---------------------------------------------------------------------

class ownmaps_flickr
{

	var $uid;
	var $from, $to, $count;
	var $lat, $lon, $rad, $latmin, $lonmin, $latmax, $lonmax;

	var $REQUEST, $RESPONSE, $JSONDEC, $DATA;

	function ownmaps_flickr()
	{
		$this->Reset();
	}

	function Reset()
	{
		global $_ownmaps_flickr_key;

		$this->setUSERID();
		$this->setFROMTO();
		$this->setBOUNDS();

		require_once( $_ownmaps_app."_phpflickr/phpFlickr.php" );

		$this->_phpFlickr = new phpFlickr( $_ownmaps_flickr_key );
	}

	function setUSERID( $UID="" )
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


		$this->REQUEST =                              array( "lat" => $this->lat );
		$this->REQUEST = array_merge( $this->REQUEST, array( "lon" => $this->lon ) );
		$this->REQUEST = array_merge( $this->REQUEST, array( "radius" => $this->rad ) );
		$this->REQUEST = array_merge( $this->REQUEST, array( "per_page" => $this->count ) );
		$this->REQUEST = array_merge( $this->REQUEST, array( "extras" => "geo,owner_name" ) );




	}

	function getRESPONSE()
	{
		$this->RESPONSE = $this->_phpFlickr->photos_search( $this->REQUEST );

//		$this->JSONDEC = json_decode( $this->RESPONSE );

		$this->DATA = $this->RESPONSE;
	}

	function addMARKER( $OMapi, $OMico, $LAT, $LON, $RAD=0.141, $CNT=10, $UID="" )
	{

		global $_ownmaps_app;

		$this->Reset();
		$this->setUSERID( $UID );
		$this->setFROMTO( 0, $CNT );
		$this->calcBOUNDS( $LAT, $LON, $RAD );
		$this->setREQUEST();
		$this->getRESPONSE();

		if ( !empty($this->DATA) ) {
//			$OMapi->addMARKER( $this->latmin, $this->lonmin, "Flickr", "", "_pin" );
//			$OMapi->addMARKER( $this->latmax, $this->lonmax, "Flickr", "", "_pin" );
//			$OMapi->addMARKER( $this->latmin, $this->lonmax, "Flickr", "", "_pin" );
//			$OMapi->addMARKER( $this->latmax, $this->lonmin, "Flickr", "", "_pin" );
			foreach( $this->DATA['photo'] as $d ) {
				$info_logo  = "<a href=\"http://flickr.com\" target=\"_blank\"><img src=\"".$_ownmaps_app."logo_flickr.png\"></a>";
				$info_image = "<a href=\""."http://www.flickr.com/photos/".$d['owner']."/".$d['id']."/"."\" target=\"_blank\"><img src=\""."https://farm".$d['farm'].".staticflickr.com/".$d['server']."/".$d['id']."_".$d['secret']."_q.jpg"."\"></a>";
//				$info_owner = "<a href=\""."http://www.flickr.com/people/".$d['owner']."/"."\" target=\"_blank\">".$d['ownername']."</a>";
				$info       = $info_logo."<br>".$info_image;
				$OMapi->addMARKER( $d['latitude'], $d['longitude'], "flickr", $info, $OMico );
			}
		}
	}

}

// =====================================================================
?>