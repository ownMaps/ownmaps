<?php
// =====================================================================
// navbar.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownmaps.org/
// ---------------------------------------------------------------------
// Your Maps Under Your Control
// =====================================================================

print "<nav class=\"navbar navbar-inverse navbar-fixed-top\">";

print "<div class=\"container-fluid\">";

print "<div class=\"navbar-header\">";

print "<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#myNavbar\">";
print "<span class=\"icon-bar\"></span>";
print "<span class=\"icon-bar\"></span>";
print "<span class=\"icon-bar\"></span>";
print "</button>";

print "<a class=\"navbar-brand\" href=\"".$_home_url."\">ownMaps</a>";

print "</div>";

print "<div class=\"navbar-collapse collapse\" id=\"myNavbar\">";

print "<ul class=\"nav navbar-nav navbar-right\">";

// ---------------------------------------------------------------------

print "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">API<span class=\"caret\"></span></a>";
print "<ul class=\"dropdown-menu\">";

if ( $_ownmaps_gmaps ) {
	$tmp_cont = "gm"; $tmp_text = "Google Maps"; if ( $_ownmaps_api==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
	print "<li".$tmp_class.">".navbarLINK( $tmp_cont, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
}

if ( $_ownmaps_leaflet ) {
	$tmp_cont = "ll"; $tmp_text = "Leaflet OSM"; if ( $_ownmaps_api==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
	print "<li".$tmp_class.">".navbarLINK( $tmp_cont, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
}

if ( $_ownmaps_openlayers ) {
	$tmp_cont = "ol"; $tmp_text = "OpenLayers"; if ( $_ownmaps_api==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
	print "<li".$tmp_class.">".navbarLINK( $tmp_cont, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
}

if ( $_ownmaps_mapbox ) {
	$tmp_cont = "mb"; $tmp_text = "Mapbox"; if ( $_ownmaps_api==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
	print "<li".$tmp_class.">".navbarLINK( $tmp_cont, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
}

print "</ul>";
print "</li>";

// ---------------------------------------------------------------------

print "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">MAP<span class=\"caret\"></span></a>";
print "<ul class=\"dropdown-menu\">";

$tmp_cont = "*"; $tmp_text = "[all]"; if ( $_ownmaps_map==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $tmp_cont, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";

$files = scandir( $_ownmaps_dat );
foreach ( $files as $key => $val ) {
	if ( substr($val,-4)==".map" ) {
		$tmp_cont = substr( $val, 0, -4 ); $tmp_text = $tmp_cont; if ( $_ownmaps_map==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
		print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $tmp_cont, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
	}
}

print "</ul>";
print "</li>";

// ---------------------------------------------------------------------

print "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">GEO<span class=\"caret\"></span></a>";
print "<ul class=\"dropdown-menu\">";

$tmp_cont = "*"; $tmp_text = "[all]"; if ( $_ownmaps_geo==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $tmp_cont, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";

$files = scandir( $_ownmaps_dat );
foreach ( $files as $key => $val ) {
	if ( substr($val,-4)==".xml" OR substr($val,-4)==".kml" OR substr($val,-5)==".json" OR substr($val,-8)==".geojson" ) {
		if ( substr($val,-4)==".xml" )		$tmp_cont = substr( $val, 0, -4 );
		if ( substr($val,-4)==".kml" )		$tmp_cont = substr( $val, 0, -4 );
		if ( substr($val,-5)==".json" )		$tmp_cont = substr( $val, 0, -5 );
		if ( substr($val,-8)==".geojson" )	$tmp_cont = substr( $val, 0, -8 );
		$tmp_text = $tmp_cont; if ( $_ownmaps_geo==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
		print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $tmp_cont, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
	}
}

$tmp_cont = "-"; $tmp_text = "[none]"; if ( $_ownmaps_geo==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $tmp_cont, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";

print "</ul>";
print "</li>";

// ---------------------------------------------------------------------

print "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">DIR<span class=\"caret\"></span></a>";
print "<ul class=\"dropdown-menu\">";

$tmp_cont = ""; $tmp_text = "[default]"; if ( $_ownmaps_dir==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $tmp_cont, $_ownmaps_set, $tmp_text )."</li>";

$files = scandir( $_ownmaps_dat );
foreach ( $files as $key => $val ) {
	if ( !in_array($val,array(".","..")) AND is_dir($_ownmaps_dat.DIRECTORY_SEPARATOR.$val) ) {
		$tmp_cont = $val; $tmp_text = $tmp_cont; if ( $_ownmaps_dir==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
		print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $tmp_cont, $_ownmaps_set, $tmp_text )."</li>";
	}
}

print "</ul>";
print "</li>";

// ---------------------------------------------------------------------

print "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">OPT<span class=\"caret\"></span></a>";
print "<ul class=\"dropdown-menu\">";

if ( $_ownmaps_panoramio ) {
	$tmp_text = "Panoramio"; if ( $_ownmaps_pan!="-" ) { $tmp_cont = "-"; $tmp_text = $tmp_text." [-]"; $tmp_class = " class=\"active\""; } else { $tmp_cont = "+"; $tmp_class = ""; }
	print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $_ownmaps_geo, $tmp_cont, "-", "-", $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
}

if ( $_ownmaps_mapillary ) {
	$tmp_text = "Mapillary"; if ( $_ownmaps_mll!="-" ) { $tmp_cont = "-"; $tmp_text = $tmp_text." [-]"; $tmp_class = " class=\"active\""; } else { $tmp_cont = "+"; $tmp_class = ""; }
	print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $_ownmaps_geo, "-", $tmp_cont, "-", $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
}
if ( $_ownmaps_flickr ) {
	$tmp_text = "Flickr"; if ( $_ownmaps_fli!="-" ) { $tmp_cont = "-"; $tmp_text = $tmp_text." [-]"; $tmp_class = " class=\"active\""; } else { $tmp_cont = "+"; $tmp_class = ""; }
	print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $_ownmaps_geo, "-", "-", $tmp_cont, $_ownmaps_dir, $_ownmaps_set, $tmp_text )."</li>";
}

print "</ul>";
print "</li>";

// ---------------------------------------------------------------------

print "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">ICO<span class=\"caret\"></span></a>";
print "<ul class=\"dropdown-menu\">";

$tmp_cont = ""; $tmp_text = "[default]"; if ( $_ownmaps_set==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $tmp_cont, $tmp_text )."</li>";

$files = scandir( $_ownmaps_ico );
foreach ( $files as $key => $val ) {
	if ( !in_array($val,array(".","..")) AND is_dir($_ownmaps_ico.DIRECTORY_SEPARATOR.$val) ) {
		$tmp_cont = $val; $tmp_text = $tmp_cont; if ( $_ownmaps_set==$tmp_cont ) { $tmp_class = " class=\"active\""; } else { $tmp_class = ""; }
		print "<li".$tmp_class.">".navbarLINK( $_ownmaps_api, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli, $_ownmaps_dir, $tmp_cont, $tmp_text )."</li>";
	}
}

print "</ul>";
print "</li>";

// ---------------------------------------------------------------------

print "</ul>";

print "</div>";

print "</div>";

print "</nav>";

// =====================================================================
?>