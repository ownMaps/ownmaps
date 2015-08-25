<?php
// =====================================================================
// api_ownmaps.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownmaps.org/
// ---------------------------------------------------------------------
// API : ownMaps
// =====================================================================

function fnOMcreateMap( $OMapi, $OMdat, $OMico, $OMmap="*", $OMgeo="*", $OMpan="-", $OMmll="-", $OMfli="-" )
{
	global $OMpanoramio, $OMmapillary, $OMflickr;

	$OMapi->createMAP();

// ---------------------------------------------------------------------

	if ( !file_exists($OMdat.$OMmap.".map") && $OMmap!="*" ) goto nomap;

	if ( $OMmap=="*" ) {
		$files = scandir( $OMdat );
		foreach ( $files as $key => $val ) {
			if ( substr($val,-4)==".map" ) {
				$RS = fopen( $OMdat.$val, "r+" );
					while ( !feof($RS) ) {
						$vars = explode( "#", fgets( $RS, 4096 ) );
						if ( !file_exists($OMico.$vars[1].".png") ) { $vars[1] = "_default"; }
						$OMapi->addMARKER( $vars[2], $vars[3], $vars[4], $vars[5], $OMico.$vars[1].".png" );
						if ( $OMpan!="-" ) { $OMpanoramio->addMARKER( $OMapi, $OMico."_panoramio".".png", $vars[2], $vars[3], 0.5, 5 ); }
						if ( $OMmll!="-" ) { $OMmapillary->addMARKER( $OMapi, $OMico."_mapillary".".png", $vars[2], $vars[3], 0.5, 5 ); }
						if ( $OMfli!="-" ) { $OMflickr->addMARKER( $OMapi, $OMico."_flickr".".png", $vars[2], $vars[3], 0.5, 5 ); }
					}
				fclose( $RS );
			}
		}
	} else {
		$RS = fopen( $OMdat.$OMmap.".map", "r+" );
			while ( !feof($RS) ) {
				$vars = explode( "#", fgets( $RS, 4096 ) );
				if ( !file_exists($OMico.$vars[1].".png") ) { $vars[1] = "_default"; }
				$OMapi->addMARKER( $vars[2], $vars[3], $vars[4], $vars[5], $OMico.$vars[1].".png" );
				if ( $OMpan!="-" ) { $OMpanoramio->addMARKER( $OMapi, $OMico."_panoramio".".png", $vars[2], $vars[3], 0.5, 5 ); }
				if ( $OMmll!="-" ) { $OMmapillary->addMARKER( $OMapi, $OMico."_mapillary".".png", $vars[2], $vars[3], 0.5, 5 ); }
				if ( $OMfli!="-" ) { $OMflickr->addMARKER( $OMapi, $OMico."_flickr".".png", $vars[2], $vars[3], 0.5, 5 ); }
			}
		fclose( $RS );
	}

nomap:

// ---------------------------------------------------------------------

	if ( !file_exists($OMdat.$OMgeo.".json") && !file_exists($OMdat.$OMgeo.".geojson") && $OMgeo!="*" ) goto nojson;

	if ( $OMgeo=="*" ) {
		$files = scandir( $OMdat );
		foreach ( $files as $key => $val ) {
			if ( substr($val,-5)==".json" OR substr($val,-8)==".geojson" ) {
				$OMapi->addGEOJSON( "http://".$_SERVER['SERVER_NAME']."/".$OMdat.$val );   // addJSON
			}
		}
	} else {
		$OMapi->addGEOJSON( "http://".$_SERVER['SERVER_NAME']."/".$OMdat.$OMgeo.".json" );   // INCLUDE .geojson
	}

nojson:

// ---------------------------------------------------------------------

	if ( !file_exists($OMdat.$OMgeo.".xml") && !file_exists($OMdat.$OMgeo.".kml") && $OMgeo!="*" ) goto noxml;

	if ( $OMgeo=="*" ) {
		$files = scandir( $OMdat );
		foreach ( $files as $key => $val ) {
			if ( substr($val,-4)==".xml" OR substr($val,-4)==".kml" ) {
				$OMapi->addKML( "http://".$_SERVER['SERVER_NAME']."/".$OMdat.$val );   // addXML
			}
		}
	} else {
		$OMapi->addKML( "http://".$_SERVER['SERVER_NAME']."/".$OMdat.$OMgeo.".kml" );   // INCLUDE .xml
	}

noxml:

// ---------------------------------------------------------------------

	$OMapi->addLAYERS();

//	$OMapi->getEVENT();

	$OMapi->initMAP();
}

// =====================================================================
?>