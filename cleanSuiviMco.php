<?php
require_once 'MonPDO.php';

$SQL = "SELECT etat_suivi FROM suivi_mco ORDER BY date_add DESC LIMIT 1" ;
$result = readSuivi( $SQL ) ;

$Week = date('W') ;
$Week = (int) $Week ;
$cell = $result['etat_suivi'] - $result['etat_suivi'] ;

$query = "TRUNCATE TABLE suivi_mco" ;
truncate($query) ;

$SQL = "INSERT INTO suivi_mco (semaines, etat_suivi, date_add ) VALUES('".$Week."' , '".$cell."' , '".date("Y-m-d H:i:s")."')"  ;
writeTable ( $SQL );

require_once 'index.php'
?>