<?php

// Connexion à la BDD ;
try 
{
	$bdd = new PDO('mysql:host=localhost;dbname=mco;charset=utf8', 'root', '');
} 
catch(exception $e) 
{
	die('Erreur '.$e->getMessage());
}

function readSuivi( $SQL )
{
	global $bdd ;
	$sth = $bdd->prepare( $SQL );
	$sth->execute();

	/* Récupération de toutes les lignes d'un jeu de résultats */
	$result = $sth->fetchAll();

	return $result [0] ;
}

function readTasks( $SQL1 )
{
	global $bdd ;
	$sth = $bdd->prepare( $SQL1 );
	$sth->execute();

	// Récupération de toutes les lignes d'un jeu de résultats
	$res = $sth->fetchAll();

	return $res ;
}

function writeTable( $SQL )
{
	global $bdd ;
	return $bdd->query( $SQL);
}

function truncate ($query) {
	global $bdd ;
	return $bdd -> query($query) ;
}

?>