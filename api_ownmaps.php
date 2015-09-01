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
	$OMapi->createMAP();

	if ( !file_exists($OMdat.$OMmap.".map") && $OMmap!="*" ) goto nomap;
	if ( $OMmap=="*" ) {
		$files = scandir( $OMdat );
		foreach ( $files as $key => $val ) {
			if ( substr($val,-4)==".map" ) {
				fnOMcreateMAPaddDATA( $OMapi, $OMdat, $OMico, $val, $OMgeo, $OMpan, $OMmll, $OMfli );
			}
		}
	} else {
		fnOMcreateMAPaddDATA( $OMapi, $OMdat, $OMico, $OMmap.".map", $OMgeo, $OMpan, $OMmll, $OMfli );
	}
nomap:

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

//	$OMapi->addCIRCLE();

	$OMapi->addLAYERS();

//	$OMapi->getEVENT();

	$OMapi->initMAP();
}

// ---------------------------------------------------------------------

function fnOMcreateMAPaddDATA( $OMapi, $OMdat, $OMico, $OMmap="*", $OMgeo="*", $OMpan="-", $OMmll="-", $OMfli="-" )
{
	global $OMpanoramio, $OMmapillary, $OMflickr;

	$RS = fopen( $OMdat.$OMmap, "r+" );
		while ( !feof($RS) ) {
			$vars = explode( "#", fgets( $RS, 4096 ) );
			switch ( strtoupper($vars[1]) ) {
				case "_POL_": break;
				case "_POG_": break;
				case "_REC_": $OMapi->addRECTANGLE( $vars[2], $vars[3], $vars[4], $vars[5] ); break;
				case "_CIR_": $OMapi->addCIRCLE( $vars[2], $vars[3], $vars[4] ); break;
				case "_PAN_": $OMpanoramio->addMARKER( $OMapi, $OMico."_panoramio".".png", $vars[2], $vars[3], 0.5, 5 ); break;
				case "_MLL_": $OMmapillary->addMARKER( $OMapi, $OMico."_mapillary".".png", $vars[2], $vars[3], 0.5, 5 ); break;
				case "_FLI_": $OMflickr->addMARKER( $OMapi, $OMico."_flickr".".png", $vars[2], $vars[3], 0.5, 5 ); break;
				case "_GJS_": break;
				case "_TJS_": break;
				case "_XML_": break;
				case "_KML_": break;
				default:
					if ( !file_exists($OMico.$vars[1].".png") ) $vars[1] = "_default";
					$OMapi->addMARKER( $vars[2], $vars[3], $vars[4], $vars[5], $OMico.$vars[1].".png" );
					if ( $OMpan!="-" ) $OMpanoramio->addMARKER( $OMapi, $OMico."_panoramio".".png", $vars[2], $vars[3], 0.5, 5 );
					if ( $OMmll!="-" ) $OMmapillary->addMARKER( $OMapi, $OMico."_mapillary".".png", $vars[2], $vars[3], 0.5, 5 );
					if ( $OMfli!="-" ) $OMflickr->addMARKER( $OMapi, $OMico."_flickr".".png", $vars[2], $vars[3], 0.5, 5 );
					break;
			}
		}
	fclose( $RS );
}

// =====================================================================
?>