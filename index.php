<?php
include "constants.inc";
include "functions.inc";
include "variables.inc";

// Helper functions
function writeArea( $o,$text,$end=0 ) {
  $coordinates = calcPixel( $o->user_n_grad,$o->user_n_gradminuten,$o->user_o_grad,$o->user_o_gradminuten );
  // Old <alt> and <title> properties:
  #printf( "\t<area shape='circle' coords='%d,%d,%d' title='%s' alt='%s'>\n",$coordinates["pointX"],$coordinates["pointY"],RADIUS,$text,$text );

  // New java-script'ish way:
  printf( "\t<area shape='circle' coords='%d,%d,%d' onmouseover=\"popup('%s',10,10)\"; onmouseout=\"kill()\">\n",
	  $coordinates["pointX"],$coordinates["pointY"],RADIUS,$text,$text,$text );
  if( $end )
    printf( "</map>" );
}

function writeImageMap() {
  $query = "SELECT * FROM ".TABLE." ORDER BY user_n_grad, user_n_gradminuten, user_o_grad, user_o_gradminuten";
  //  echo $query;
  $result = mysql_query( $query );
  echo mysql_error();

  printf( "<map name='usermap'>\n" );

  $p = "";
  $names = "";
  while( $o = mysql_fetch_object( $result )) {
    if( $p == "" ) {
      // first run
      $coordinates = calcPixel( $o->user_n_grad,$o->user_n_gradminuten,$o->user_o_grad,$o->user_o_gradminuten );
      $names = "<li>".$o->user_name."</li>";
      $p = $o;
    } else {
      // normal run
      if( pointsMatch($o,$p) == TRUE ) {
	$names .= "<li>".$o->user_name."</li>";
	$p = $p;
      } else {
	writeArea( $p,$names );
	$names = "<li>".$o->user_name."</li>";
	$p = $o;
      }
    }
  }
  // process last set of values
  writeArea( $p,$names,1 );
}
// EO header
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html">
<title>Userkarte @ gentoo.de</title>
<link rel="stylesheet" href="/css/index.css" type="text/css">
<link rel="stylesheet" href="/css/info.css" type="text/css">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="./css/popup.css">

</head>
<body bgcolor="white"><div id="dek"></div>
<script type="text/javascript" src="./script/popup.js"></script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td class="logo" width="200" rowspan="2" align="center"><a href="/"><img src="/img/logo-2004.png" height="110" width="160" alt="Gentoo"></a></td>
<td bgcolor="white" colspan="3"><img src="/img/blank.gif" height="78"></td>
</tr>
<tr><td class="navi" colspan="4">
<ul>
<li><a href="/">Neuigkeiten</a></li>
<li><a href="/proj/de/index.xml">Projekt</a></li>
<li><a href="/doc/de/index.xml">Dokumentation</a></li>
<li><a href="/main/de/foren.xml">Foren</a></li>
<li><a href="/main/de/listen.xml">Listen</a></li>
<li><a href="/main/de/irc.xml">IRC</a></li>

<li><a href="/main/de/downloads.xml">Downloads</a></li>
<li><a href="/userkarte/">Userkarte</a></li>
<li><a href="/main/de/kontakt.xml">Kontakt</a></li>
</ul>
</td></tr>
<tr><td bgcolor="white" height="5" colspan="4" class="unpadding"></td></tr>
<tr><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
<td width="20" class="unpadding"></td>
<td class="blank" height="25" colspan="4"></td>
</tr></table></tr>
<tr><td class="unpadding"><img src="/img/blank.gif" height="5"></td></tr>
<tr><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
<td width="20" class="unpadding"></td></tr></table></tr>

<?php
if( isset($values["N_grad"]) &&
    isset($values["N_gradminuten"]) &&
    isset($values["O_grad"]) &&
    isset($values["O_gradminuten"]) &&
    !$_REQUEST["bttnyes"] && !$_REQUEST["bttnno"]
    )
{
  $coordinates = calcPixel( $values["N_grad"],$values["N_gradminuten"],$values["O_grad"],$values["O_gradminuten"] );
  if(( $coordinates["pointX"] >= 0 ) && ( $coordinates["pointX"] <= IMAGE_WIDTH ) &&
     ( $coordinates["pointY"] >= 0 ) && ( $coordinates["pointY"] <= IMAGE_HEIGHT )) {
    $message = "Die gew&auml;hlten Koordinaten wurden auf der Karte angezeigt.";
    $coords_okay = TRUE;
  } else {
    $message = "Koordinaten liegen ausserhalb des Bildes.";
    $coords_okay = FALSE;
  }
  $text = $name." @ ".$values["N_grad"]."°".$values["N_gradminuten"]."'N/".$values["O_grad"]."°".$values["O_gradminuten"]."'O";
?>

<table border="0" align="center" width='100%'>
<tr>
   <td class='nav' align='center'><font size="+2"><b>Vorschau</b></font></td>
</tr>
<tr>
   <td class='nav' align='center'>
     <img style='border-color:#000000' src="genpic.php?mode=1&N_grad=<?=$values["N_grad"]?>&N_gradminuten=<?=$values["N_gradminuten"]?>&O_grad=<?=$values["O_grad"]?>&O_gradminuten=<?=$values["O_gradminuten"]?>"><br><br>
     <?=$message?><br>
     <form name="dataform" action='<?=$HTTP_SERVER_VARS["REQUEST_URI"]?>' method="post">
     <b>Wollen Sie ihre Werte (<?=$text?>) in der Datenbank speichern?<b><br>
     <input type="hidden" name="N_grad" value='<?=$values["N_grad"]?>'>
     <input type="hidden" name="N_gradminuten" value='<?=$values["N_gradminuten"]?>'>
     <input type="hidden" name="O_grad" value='<?=$values["O_grad"]?>' >
     <input type="hidden" name="O_gradminuten" value='<?=$values["O_gradminuten"]?>'>
     <input type="hidden" name="name" value='<?=$name?>'>
     <input type="hidden" name="email" value='<?=$email?>'>
     <input type="submit" name="bttnyes" value="Ja" <?php if (!$coords_okay) echo "disabled"; ?>>&nbsp;
     <input type="submit" name="bttnno" value="Nein">
     </form>
     <br><br>
   </td>
</tr>
</table>

<?php 
} elseif( $step >= 1 ) {
  switch( $step ) {
    case 1:
?>
      <table border="0" width="100%">
      <tr>
	 <td colspan="3" width="100%" height="30"></td>	 
      </tr>
      <tr>
	 <td width="25%"></td>
	 <td width="50%" class="nav">
	 <form name="adminpanel" method="post" action="<?=$HTTP_SERVER_VARS["REQUEST_URI"]?>">
	 <input type="hidden" name="admin" value="2">
	 <table border="0" width="100%">
	 <tr>
  	   <td colspan="2" width="100%" align="center" height="50"><font size="+2"><b>::Login::</b></font></td>
	 </tr>
	 <tr>
	   <td width="50%" align="right">Username:</td>
	   <td width="50%" align="left"><input type="text" name="admin_username"></td>
	 </tr>
	 <tr>
	   <td align="right">Password:</td>
	   <td align="left"><input type="password" name="admin_password"></td>
	 </tr>
	 <tr>
	   <td colspan="2" align="center"><input type="submit" value="Login"></td>
	 </tr>
	 </table>
	 </form>
	 </td>
	 <td width="25%"></td>
      </tr>

      </table>
<?php
    break;
    case 2:
    case 3:
      if(( $_REQUEST["admin_username"] == ADMIN_USERNAME ) &&
	 ( $_REQUEST["admin_password"] == ADMIN_PASSWORD )) {
	if( $step == 2 ) {
	  if( isset( $_REQUEST["dodelete"] )) {
	    dbconnect();
	    $keys = array_keys($_REQUEST);
	    for( $num=0;$num<count( $keys );$num++ ) {
	      if( (int) $keys[$num] ) {
		$query = "DELETE FROM ".TABLE." WHERE user_id = ".$keys[$num];
		mysql_query( $query );
		echo mysql_error();
	      }
	    }
	  } 
?>
      <table border="0" width="100%">
      <tr>
	 <td width="2%"></td>
	 <td width="96%" class="nav">
	 <form name="adminpanel" method="post" action="<?=$HTTP_SERVER_VARS["REQUEST_URI"]?>">
	 <input type="hidden" name="admin" value="2">
	 <input type="hidden" name="admin_username" value="<?=ADMIN_USERNAME?>">
	 <input type="hidden" name="admin_password" value="<?=ADMIN_PASSWORD?>">
	 <table border="0" width="100%">
	 <tr>
  	   <td colspan="8" width="100%" align="center" height="50"><font size="+2"><b>Datenbestand</b></font></td>
	 </tr>
	 <tr>
	   <td width="2%" align="center"></td>
	   <td width="16%" align="center"><b>L&ouml;schen?</b></td>
	   <td width="16%" align="center"><b>Name</b></td>
	   <td width="16%" align="center"><b>Grad N</b></td>
	   <td width="16%" align="center"><b>Gradminuten N</b></td>
	   <td width="16%" align="center"><b>Grad O</b></td>
	   <td width="16%" align="center"><b>Gradminuten O</b></td>
	   <td width="2%" align="center"></td>
	 </tr>
<?php
	  dbconnect();
	  $query = "SELECT * FROM ".TABLE;
	  $result = mysql_query( $query );
	  echo mysql_error();
	  while( $o = mysql_fetch_object( $result )) {
	    printf( "\t<tr align='center'>\n\t\t<td></td>\n" );
	    printf( "\t\t<td><input type='checkbox' name='%s'</td>\n",$o->user_id,$o->user_id );
	    printf( "\t\t<td>%s</td>\n",$o->user_name );
	    printf( "\t\t<td>%d</td>\n",$o->user_n_grad );
	    printf( "\t\t<td>%d</td>\n",$o->user_n_gradminuten );
	    printf( "\t\t<td>%d</td>\n",$o->user_o_grad );
	    printf( "\t\t<td>%d</td>\n",$o->user_o_gradminuten );
	    printf( "\t\t<td></td>\n\t</tr>\n" );
	  }
?>
	 <tr>
  	   <td colspan="8" width="100%" align="center" height="50">
	    <input type="submit" name="dodelete" value="L&ouml;schen">
	    <input type="submit" name="dosql" value="SQL generieren"></form>
	   </td>
	 </tr>

<?php
	  if( isset( $_REQUEST["dosql"] )) {
	    dbconnect();
	    $query = "SELECT * FROM tblUsers";
	    $result = mysql_query( $query );
	    echo mysql_error();
	    while( $o = mysql_fetch_object( $result )) {
	      print( "\t<tr>\n\t\t<td colspan='2'></td>\n" );
	      printf( "\t\t<td colspan='4'>INSERT INTO %s VALUES(%d,\"%s\",%d,%d,%d,%d,'%s');</td>\n",
		      TABLE,
		      $o->user_id,
		      preg_replace('/\'|\"/','&quot;',$o->user_name),
		      $o->user_n_grad,
		      $o->user_n_gradminuten,
		      $o->user_o_grad,
		      $o->user_o_gradminuten,
		      $o->user_email );
	      print( "\t\t<td colspan='2'></td>\n\t</tr>\n" );
	    }
	  }
?>
	 </table>
	 </form>
	 </td>
	 <td width="2%"></td>
      </tr>
      </table>

<?php
	} elseif( $step == 3 ) {
	  // TODO:
	  // Possible implementation of 'modify data'
	  ;;
	}
      } else {
	echo "Unauthorized";
      }
    break;
  }
?>

<?php
} else {
 dbconnect();
 // Insert pending values into database
 if( isset($values["N_grad"]) &&
     isset($values["N_gradminuten"]) &&
     isset($values["O_grad"]) &&
     isset($values["O_gradminuten"]) && $_REQUEST["bttnyes"] ) {
   $query = "INSERT INTO ".TABLE."(user_name,user_n_grad,user_n_gradminuten,user_o_grad,user_o_gradminuten,user_email) VALUES(\"".
     $name."\",".$values["N_grad"].",".$values["N_gradminuten"].",".$values["O_grad"].",".$values["O_gradminuten"].
     ",\"".$email."\")";
   mysql_query( $query );
   echo mysql_error();
 }
 writeImageMap();
?>

<table border="0">
<tr height="50">
   <td class="nav" colspan="2" align="center"><font size="+2"><b>Die gentoo.de Userkarte</b></font></td>
</tr>
<tr height="10">
   <td colspan="2" align="center"></td>
</tr>
<tr>

   <td class="nav">
     <table border="0" align="center">
     <tr height='20'>
        <th><font size="+1"><b>Aktuelle Ansicht</b></font></th>
     </tr>
     <tr>
       <td align="center"><img style='border-color:#000000' src="genpic.php?mode=0" usemap="#usermap" border="1" color="#000000"></td>
     </tr>
     </table>
   </td>
   <td valign="top" class="nav" width="25%">
     <table border="0" valign="top">
     <tr>
        <th valign="top"><font size="+1"><b>Neuer Eintrag</b></font></th>
     </tr>
     <tr>
        <td><p align="justify">Falls Sie ihre Koordinaten nicht zur Hand haben, k&ouml;nnen sie diese kostenfrei auf der Seite
        <a href='http://www.themamundi.de/aws/tabel/tbmain.htm'>Thema Mundi</a> erfahren.</p></td>
     </tr>
     <tr>
	<form name="naviform" action='<?=$HTTP_SERVER_VARS["REQUEST_URI"]?>' method="post">
        <table border="0" align="center">
        <tr>
           <th colspan="3"><b>Koordinaten</b></th>
        </tr>
        <tr>
           <td></th>
           <td align="right">Grad</td>
           <td align="right">Minuten</td>
        </tr>
        <tr align="center">
           <td align="right">Nord</td>
           <td><input type="text" size="2" maxlength="2" name="N_grad"></td>
           <td><input type="text" size="2" maxlength="2" name="N_gradminuten"></td>
        </tr>
        <tr align="center">
           <td align="right">Ost</td>
           <td><input type="text" size="2" maxlength="2" name="O_grad"></td>
           <td><input type="text" size="2" maxlength="2" name="O_gradminuten"></td>
        </tr>
        <tr>
           <th colspan="3"><b>Name</b></th>
        </tr>
        <tr>
           <td colspan="3"><input type="text" name="name"></td>
        </tr>
        <tr>
           <th colspan="3"><b>E-Mail (wird verfremdet)</b></th>
        </tr>
        <tr>
           <td colspan="3"><input type="text" name="email"></td>
        </tr>
        <tr>
           <td colspan="3" align="center"><input type="submit" value="Eintragen"></td>
        </tr>
        </table>
        </form>
     </tr>
     <tr>
        <td colspan="2" class="nav">
        <table border="0" width="100%">
        <tr>
           <td></td>
           <td><ul>
<?php
$query = "SELECT user_name,user_email FROM ".TABLE." ORDER BY user_name";
$result = mysql_query( $query );
echo mysql_error();
 $num = mysql_num_rows( $result );
if( !$num ) {
  printf( "<li>Keine Eintr&auml;ge</li>\n</ul>\n" );
} else {
  while( $o = mysql_fetch_object( $result )) {
#    $email = str_replace(array("@"),array(" at "),$o->user_email);
#    $email = str_replace(array("."),array(" dot "),$email);
#    printf( "<li>%s%s</li>", $o->user_name,$email? " &bull; &lt;$email&gt;" : "" );
#    printf( "<li>%s</li>", $o->user_name,$email? " &bull; " : "" );
  }
  printf( "</ul>\n%d Eintraege insgesamt\n", $num );
}
?>
           </td>
           <td></td>
        </tr>
        </table>
     </tr>
     </table>
</tr>
</table>

   </td>
</tr>
</table>


<?php } ?>
<table border="0" width="100%">
<tr height="10">
   <td></td>
</tr>
<tr>
   <td align="right" class="nav">
   <table border="0" width="100%">
   <tr>
      <td width="50%" align="left"><font size="-1">Version <?=VERSION?></font></td>
      <td width="50%" align="right"><font size="-1">Written by <a href="mailto:tilman.klar@gmx.de" class="href">phoen][x</a> in '03<br>and refurbished by <a href="mailto:ian@gentoo.org">ian!</a></font></td>
   </tr>
   </table>
   </td>
</tr>
</table>
</body>
</html>
