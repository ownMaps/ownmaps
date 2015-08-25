<?php
// =====================================================================
// config.php
// ---------------------------------------------------------------------
// ownMaps - Copyright (C) 2015 by J. Kerchel - http://www.ownmaps.org/
// ---------------------------------------------------------------------
// Your Maps Under Your Control
// =====================================================================

$_home_url = "#";

$_ctrl_bar = TRUE;

if ( isset($_GET["bar"]) AND $_GET["bar"]=="-" ) $_ctrl_bar = FALSE;

// ---------------------------------------------------------------------

$_ownmaps_app = "";
$_ownmaps_dat = $_ownmaps_app."d/";
$_ownmaps_ico = $_ownmaps_app."i/";

$_ownmaps_api = "gm";
$_ownmaps_map = "*";
$_ownmaps_geo = "*";
$_ownmaps_pan = "-";
$_ownmaps_mll = "-";
$_ownmaps_fli = "-";
$_ownmaps_dir = "";
$_ownmaps_set = "";

// ---------------------------------------------------------------------

function navbarLINK( $API, $MAP, $GEO, $PAN, $MLL, $FLI, $DIR, $SET, $TXT )
{
	global $_ownmaps_dir;

	if ( $_ownmaps_dir==$DIR ) {
		return "<a href=\"http://".$_SERVER['SERVER_NAME']."?api=".$API."&map=".$MAP."&geo=".$GEO."&pan=".$PAN."&mll=".$MLL."&fli=".$FLI."&dir=".$DIR."&set=".$SET."\">".$TXT."</a>";
	} else {
		return "<a href=\"http://".$_SERVER['SERVER_NAME']."?api=".$API."&dir=".$DIR."&set=".$SET."\">".$TXT."</a>";
	}
}

// ---------------------------------------------------------------------

if ( isset($_GET["api"]) ) $_ownmaps_api = strtolower($_GET["api"]);
if ( isset($_GET["map"]) ) $_ownmaps_map = strtolower($_GET["map"]);
if ( isset($_GET["geo"]) ) $_ownmaps_geo = strtolower($_GET["geo"]);
if ( isset($_GET["pan"]) ) $_ownmaps_pan = strtolower($_GET["pan"]);
if ( isset($_GET["mll"]) ) $_ownmaps_mll = strtolower($_GET["mll"]);
if ( isset($_GET["fli"]) ) $_ownmaps_fli = strtolower($_GET["fli"]);

if ( !isset($_GET["dir"]) OR $_GET["dir"]=="" ) {
	$_ownmaps_dir = "";
} else {
	$_ownmaps_dir = strtolower($_GET["dir"]); $_ownmaps_dat .= $_ownmaps_dir."/";
}

if ( !isset($_GET["set"]) OR $_GET["set"]=="" ) {
	$_ownmaps_set = "";
} else {
	$_ownmaps_set = strtolower($_GET["set"]); $_ownmaps_ico .= $_ownmaps_set."/";
}

// =====================================================================
?>