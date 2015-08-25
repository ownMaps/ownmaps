<?php
// =====================================================================
// index.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownmaps.org/
// ---------------------------------------------------------------------
// Your Maps Under Your Control
// =====================================================================

include( "config.php" );

print "<!DOCTYPE html>";

print "<html>";

print "<head>";

print "<title>Map created with ownMaps</title>";

print "<meta charset=\"utf-8\">";

print "<!--[if lt IE 9]>";
print "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.js\"></script>";
print "<![endif]-->";

print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";

print "<link rel=\"stylesheet\" href=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css\">";
print "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js\"></script>";
print "<script src=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js\"></script>";

print "<style>html, body, main { height: 100%; margin: 0px; padding: 0px }</style>";

if ( $_ctrl_bar ) print "<link rel=\"stylesheet\" href=\"navbar.css\">";

include( $_ownmaps_app."ownmaps.php" );

print "</head>";

print "<body>";

if ( $_ctrl_bar ) include( "navbar.php" );

print "<main>";

switch ( $_ownmaps_api ) {
	case "gm": $OM = $OMgmaps;      break;
	case "ll": $OM = $OMleaflet;    break;
	case "ol": $OM = $OMopenlayers; break;
	case "mb": $OM = $OMmapbox;     break;
	default:   goto nomap;          break;
}

fnOMcreateMap( $OM, $_ownmaps_dat, $_ownmaps_ico, $_ownmaps_map, $_ownmaps_geo, $_ownmaps_pan, $_ownmaps_mll, $_ownmaps_fli );

nomap:

print "</main>";

print "</body>";

print "</html>";

// =====================================================================
?>