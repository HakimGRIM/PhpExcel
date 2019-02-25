<?php 
	require_once 'MonPDO.php';

	//Lecture de la table suivi_mco ;
	$SQL = "SELECT * FROM suivi_mco ORDER BY date_add DESC LIMIT 1" ;
	$result = readSuivi( $SQL ) ;
	
	//Lecture de la table tasks_list
	$SQL1 = "SELECT techno, constructeur, operation, frequence, suivi_par, mop FROM tasks_list" ;
	$res = readTasks( $SQL1 ) ;
	$nbRecord = count($res) ;
?>

<!DOCTYPE html>

<html>
    <head>
        <title>MCO</title>
		<link href="./css/mco.css" rel="stylesheet">
        <meta charset= "utf-8">
		
    </head>    
    <body>
		<form method="post" action="readXLS.php">
		<input type="submit" id="sfr" class="class" value="">
		</form>
		<!--img src="./images/sfr.png" alt="logo SFR" id="sfr"/-->
		<img src="./images/altice.jpg" alt="logo ALTICE" id="altice"/>
		<div class="container">
			<div id="titre"><span class="red">S</span>uivi <span class="red">MCO</span></div>
			<div id="contenu">
				<span id="semaine">Semaine <?php echo $result['semaines'] ; ?>:</span>
				<span id="etat"><?php echo number_format($result ['etat_suivi'], 2) ; ?> %</span>
			</div>
			<div id="date" class="red"><?php echo $result['date_add'] ; ?></div>
		</div>
		<div class="container">
			<div id="titre1"><span>Tâches Restant A Faire</span></div>
			<div id="taches">
				<?php 
					if($nbRecord > 5) { 
				?>
					<div id="titre1"><span class="red"><?php print $nbRecord ?></span>
				<?php
					} else {
				?>
				<table border="1" class="text">
					<?php
						$nbR = $nbRecord ;
						$nbC = 6 ; 
					?>
					<tbody>
						<?php
							for($i = 0 ; $i < $nbR ; $i ++) {
						?>
						<tr>
							<?php
								for($j = 0 ; $j < $nbC ; $j ++) {
							?>
							<td> <?php  echo $res[$i][$j] ?> </td>
							<?php
								}
							?>
						</tr>
						<?php
							}
						?>
					</tbody>
				</table>
				<?php
					} 
				?>
				</div>
			</div>
		</div>
    </body>
</html>

