<?php
// =====================================================================
// ownmaps.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownmaps.org/
// ---------------------------------------------------------------------
// Your Maps Under Your Control
// ---------------------------------------------------------------------

$_ownmaps_json = TRUE;
$_ownmaps_xml  = TRUE;

$_ownmaps_gmaps = TRUE;

$_ownmaps_leaflet = TRUE;

$_ownmaps_openlayers = FALSE;

$_ownmaps_mapbox        = FALSE;
$_ownmaps_mapbox_token  = "";
$_ownmaps_mapbox_map    = "";
$_ownmaps_mapbox_secret = "";

$_ownmaps_panoramio      = TRUE;
$_ownmaps_panoramio_user = "";

$_ownmaps_mapillary        = FALSE;
$_ownmaps_mapillary_user   = "";
$_ownmaps_mapillary_id     = "";
$_ownmaps_mapillary_secret = "";

$_ownmaps_flickr        = FALSE;
$_ownmaps_flickr_user   = "";
$_ownmaps_flickr_key    = "";
$_ownmaps_flickr_secret = "";

if ( file_exists($_ownmaps_app."ownmaps.dat") ) include( $_ownmaps_app."ownmaps.dat" );

// ---------------------------------------------------------------------

if ( $_ownmaps_json )		include( $_ownmaps_app."api_json.php" );
if ( $_ownmaps_xml )		include( $_ownmaps_app."api_xml.php" );

if ( $_ownmaps_gmaps )		include( $_ownmaps_app."api_gmaps.php" );
if ( $_ownmaps_leaflet )	include( $_ownmaps_app."api_leaflet.php" );
if ( $_ownmaps_openlayers )	include( $_ownmaps_app."api_openlayers.php" );
if ( $_ownmaps_mapbox )		include( $_ownmaps_app."api_mapbox.php" );

if ( $_ownmaps_panoramio )	include( $_ownmaps_app."api_panoramio.php" );
if ( $_ownmaps_mapillary )	include( $_ownmaps_app."api_mapillary.php" );
if ( $_ownmaps_flickr )		include( $_ownmaps_app."api_flickr.php" );

if ( $_ownmaps_json )		$OMjson       = NEW ownmaps_json();
if ( $_ownmaps_xml )		$OMxml        = NEW ownmaps_xml();

if ( $_ownmaps_gmaps )		$OMgmaps      = NEW ownmaps_gmaps();
if ( $_ownmaps_leaflet )	$OMleaflet    = NEW ownmaps_leaflet();
if ( $_ownmaps_openlayers )	$OMopenlayers = NEW ownmaps_openlayers();
if ( $_ownmaps_mapbox )		$OMmapbox     = NEW ownmaps_mapbox();

if ( $_ownmaps_panoramio )	$OMpanoramio  = NEW ownmaps_panoramio();
if ( $_ownmaps_mapillary )	$OMmapillary  = NEW ownmaps_mapillary();
if ( $_ownmaps_flickr )		$OMflickr     = NEW ownmaps_flickr();

// ---------------------------------------------------------------------

include( $_ownmaps_app."api_ownmaps.php" );

// =====================================================================
?>