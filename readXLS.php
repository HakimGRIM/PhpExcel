<?php

// Charger la librairie PHPExcel ;
require_once './PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
require_once 'MonPDO.php';

//Copie fichier ;
$Mon_Fichier_XLSX = '"U:\MRE\DEX\MCO\STR\Interne-STR\Suivi Réseau\MCO\Fichier de suivi MCO.xlsx"' ;
exec ( 'copy ' . $Mon_Fichier_XLSX . ' .\Fichier_de_suivi_MCO.xlsx' ) ;

// Chargement du fichier Excel
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load('Fichier_de_suivi_MCO.xlsx');
$sheet = $objPHPExcel->getActiveSheet() ;

/**
* récupération de la première feuille du fichier Excel
* @var PHPExcel_Worksheet $sheet
*/

// Définit le fuseau horaire par défaut à utiliser. Disponible depuis PHP 5.1
date_default_timezone_set('Europe/Paris');

//Récuperer l'année et la semaine en cours dans un tableau ;
$YearWeek = [];
$YearWeek[0] = date('Y');
$YearWeek[1] = date('W') ;
$YearWeek[0] = (int) $YearWeek[0] ;
$YearWeek[1] = (int) $YearWeek[1] ;

// Calcul du néro de colomne de la semaine en cours ;
$cl = 6 + (($YearWeek[0] - 2011) * 52) + $YearWeek[1] ;

//Récuperation de la valeur de l'état d'avancement du MCO ;
$cell = $sheet->getCellByColumnAndRow($cl, 2) -> getOldCalculatedValue() ;

// Récuperation de toute les cellules fusionné ;
$tabMerged = $objPHPExcel->setActiveSheetIndex("0")->getMergeCells();

//$nbr = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();


// Function qui verifie si une cellule est fusionné ;
function isMerged($Cellule,$tabMerged){
   foreach($tabMerged as $MergedCel){
       if($Cellule->isInRange($MergedCel)){
           return explode(":", $MergedCel)[0];
       }
   }
   return $Cellule->getCoordinate();
}

// Clean tasks_list ;
$query = "TRUNCATE TABLE tasks_list" ;
truncate($query) ;

//Tableau a deux dimension qui va contenir les informations sur les opérations.
$ligne = array();
$nbRows = 55 ;
$cptLigne = 0 ;

$rr = 4 ;
while(1){
   $val = $sheet->getCellByColumnAndRow(2, $rr) -> getValue();
   //print $val ;
   if($val === NULL){
      break ;
   } else {
      $rr ++ ;
   }
}
echo $rr ;

for($row = 4 ; $row < $nbRows ; $row++){
   $etat = $sheet->getCellByColumnAndRow($cl, $row) -> getOldCalculatedValue();
   if ($etat == "A FAIRE"){
      $cel =  isMerged($sheet->getCellByColumnAndRow(0, $row),$tabMerged);
      $ligne[$cptLigne][0] = $sheet->getCell("$cel")->getValue();
      $cel =  isMerged($sheet->getCellByColumnAndRow(1, $row),$tabMerged);
      $ligne[$cptLigne][1] = $sheet->getCell("$cel")->getValue();
      $ligne[$cptLigne][2] = $sheet->getCellByColumnAndRow(2, $row) -> getValue();
      $ligne[$cptLigne][3] = $sheet->getCellByColumnAndRow(3, $row) -> getValue();
      $ligne[$cptLigne][4] = $sheet->getCellByColumnAndRow(4, $row) -> getValue();
      $ligne[$cptLigne][5] = $sheet->getCellByColumnAndRow(5, $row) -> getValue();
      $ligne[$cptLigne][6] = $sheet->getCellByColumnAndRow($cl, $row) -> getOldCalculatedValue();
      $SQL1 = "INSERT INTO tasks_list(techno,constructeur,operation,frequence,suivi_par,mop,etat) 
              VALUES('".$ligne[$cptLigne][0]."',
                     '".$ligne[$cptLigne][1]."',
                     '".$ligne[$cptLigne][2]."', 
                     '".$ligne[$cptLigne][3]."', 
                     '".$ligne[$cptLigne][4]."',
                     '".$ligne[$cptLigne][5]."', 
                     '".$ligne[$cptLigne][6]."')"  ;
      $cptLigne ++ ;
      writeTable($SQL1);
   }
}

// Ajout d'une nouvelle ligne dans la table suivi_mco ;
$SQL = "INSERT INTO suivi_mco (semaines, etat_suivi, date_add ) VALUES('".$YearWeek[1]."' , '".$cell."' , '".date("Y-m-d H:i:s")."')"  ;
writeTable ( $SQL );

// Suppression du fichier Excel ;
unlink('Fichier_de_suivi_MCO.xlsx') ;

//require_once 'index.php' ;

?>