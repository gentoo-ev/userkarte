<?php
include "constants.inc";
include "functions.inc";
include "variables.inc";

/***
 * Funktion drawPoint()
 *
 * Zeichnet einen Punkt auf die Karte.
 * Parameter:
 *   $picture (Das Bildobjekt auf dem gezeichnet werden soll)
 *   $n_grad,$n_gradminuten,$o_grad,$o_gradminuten (die Koordinaten)
 *   $innercolor,$outercolor (die Farben des Punktes)
 */
function drawPoint( $picture,$n_grad,$n_gradminuten,$o_grad,$o_gradminuten,$innercolor,$outercolor ) {
  $coordinates = calcPixel( $n_grad,$n_gradminuten,$o_grad,$o_gradminuten );

  if(( $coordinates["pointX"] >= 0 ) && ( $coordinates["pointX"] <= imagesx( $picture )) &&
     ( $coordinates["pointY"] >= 0 ) && ( $coordinates["pointY"] <= imagesy( $picture ))) {

    for( $count = 0 ; $count < ( RADIUS * 2 ) ; $count++ ) {
      imageellipse( $picture,
		    $coordinates["pointX"],
		    $coordinates["pointY"],
		    $count,
		    $count,
		    $innercolor );
    }
    imageellipse( $picture, $coordinates["pointX"], $coordinates["pointY"], RADIUS * 2, RADIUS * 2, $outercolor );
    return 0;
  } else {
    return 1;
  }
}

#$orange = imagecolorallocate( $im, 220, 210, 60 );

header("Content-type: image/png");
#header("Content-type: text/plain");
$picture = imagecreatefrompng ( IMAGE );
$innercolor = ImageColorAllocate( $picture, 251, 255, 142 );
$outercolor = ImageColorAllocate( $picture, 147, 149, 082 );
$innermedium = ImageColorAllocate( $picture, 255, 0, 0 );
$outermedium = ImageColorAllocate( $picture, 200, 0, 0 );
/*
 * $mode
 *    Welche Karte wird gezeichnet? Vorschau oder Gesamt?
 *  
 * 0: Gesamt
 * 1: Vorschau
 *
 */
$mode = $_GET['mode'];

if( $mode == 0 ) {
  // Gesamt
  dbconnect();
  $query = "SELECT * FROM ".TABLE." ORDER BY user_n_grad, user_n_gradminuten, user_o_grad, user_o_gradminuten";
  $result = mysql_query( $query );
  $p = "";
  while( $o = mysql_fetch_object( $result )) {
    if( $p == "" ) {
      // first run
      drawPoint( $picture,$o->user_n_grad,$o->user_n_gradminuten,$o->user_o_grad,$o->user_o_gradminuten,$innercolor,$outercolor );
    } else {
      // normal run
      if( pointsMatch($o,$p) == FALSE ) {
	drawPoint( $picture,$o->user_n_grad,$o->user_n_gradminuten,$o->user_o_grad,$o->user_o_gradminuten,$innercolor,$outercolor );
      }
    }
    $p = $o;
  }

  //  $medium = mysql_fetch_array( mysql_query( "SELECT AVG( ".TABLE.".user_n_grad + ".TABLE.".user_n_gradminuten / 60 ) AS med_n, AVG( ".TABLE.
  //		 ".user_o_grad + ".TABLE.".user_o_gradminuten / 60 ) AS med_o FROM ".TABLE ), MYSQL_ASSOC );

  $medium = mysql_fetch_array( mysql_query( "SELECT AVG( user_n_grad ), AVG( user_n_gradminuten ), AVG( user_o_grad ), AVG( user_o_gradminuten ) FROM ".TABLE ), MYSQL_NUM );
  drawpoint( $picture,$medium[0],$medium[1],$medium[2],$medium[3],$innermedium,$outermedium );
} elseif( $mode == 1 ) {
  // Vorschau
  drawPoint( $picture,$values["N_grad"],$values["N_gradminuten"],$values["O_grad"],$values["O_gradminuten"],$innercolor,$outercolor );
} else {
  die( "Unknown mode. Terminating." );
}
imagepng( $picture );
imagedestroy( $picture );
?>
