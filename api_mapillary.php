<?php
// =====================================================================
// api_mapillary.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownmaps.org/
// ---------------------------------------------------------------------
// API : Mapillary
// ---------------------------------------------------------------------

class ownmaps_mapillary
{

	var $uid;
	var $from, $to, $count;
	var $lat, $lon, $rad, $latmin, $lonmin, $latmax, $lonmax;

	var $REQUEST, $RESPONSE, $JSONDEC, $DATA;

	function ownmaps_mapillary()
	{
		$this->Reset();
	}

	function Reset()
	{
		$this->setUSERID();
		$this->setFROMTO();
		$this->setBOUNDS();




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

		global $_ownmaps_mapillary_id;

		$this->REQUEST  = 'https://a.mapillary.com/v2/search/im/geojson?client_id='.$_ownmaps_mapillary_id;

		$this->REQUEST .= '&min_lat='.$this->latmin;
		$this->REQUEST .= '&max_lat='.$this->latmax;
		$this->REQUEST .= '&min_lon='.$this->lonmin;
		$this->REQUEST .= '&max_lon='.$this->lonmax;
		$this->REQUEST .= '&limit='.  $this->count;




	}

	function getRESPONSE()
	{
		$this->RESPONSE = file_get_contents( $this->REQUEST );

		$this->JSONDEC = json_decode( $this->RESPONSE );

		$this->DATA = $this->JSONDEC;
	}

	function addMARKER( $OMapi, $OMico, $LAT, $LON, $RAD=0.282, $CNT=10, $UID="" )
	{

		global $_ownmaps_app;

		$this->Reset();
		$this->setUSERID( $UID );
		$this->setFROMTO( 0, $CNT );
		$this->calcBOUNDS( $LAT, $LON, $RAD );
		$this->setREQUEST();
		$this->getRESPONSE();

		if ( !empty($this->DATA) ) {
//			$OMapi->addMARKER( $this->latmin, $this->lonmin, "Mapillary", "", "_pin" );
//			$OMapi->addMARKER( $this->latmax, $this->lonmax, "Mapillary", "", "_pin" );
//			$OMapi->addMARKER( $this->latmin, $this->lonmax, "Mapillary", "", "_pin" );
//			$OMapi->addMARKER( $this->latmax, $this->lonmin, "Mapillary", "", "_pin" );
			foreach( $this->DATA->features as $f ) {
				$info_logo  = "<a href=\"http://mapillary.com\" target=\"_blank\"><img src=\"".$_ownmaps_app."logo_mapillary.png\"></a>";
				$info_image = "<a href=\""."https://www.mapillary.com/map/imgl/".$f->properties->key."/photo"."\" target=\"_blank\"><img src=\""."http://images.mapillary.com/".$f->properties->key."/thumb-320.jpg"."\"></a>";
//				$info_owner = "";
				$info       = $info_logo."<br>".$info_image;
				$OMapi->addMARKER( $f->geometry->coordinates[1], $f->geometry->coordinates[0], "Mapillary", $info, $OMico );
			}
		}
	}

}

// =====================================================================
?>