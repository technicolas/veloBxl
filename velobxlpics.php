<html>
	<head>
		<meta charset="utf-8" />
		<title>Vélos Bxl (avec images)</title>
		<link rel="stylesheet" type="text/css" href="velobxl.css" />
	</head>
	<body>
		<a href="<?php $_SERVER['PHP_SELF']; ?>">Actualiser la page</a>
		
		<?php
			// Récupération du nombre de capteurs disponibles pour le moment sur le réseau:
			// ----------------------------------------------------------------------------
			$json = file_get_contents("https://data.mobility.brussels/geoserver/bm_bike/wfs?service=wfs&version=1.1.0&request=GetFeature&typeName=bm_bike:rt_counting&outputFormat=json&srsName=EPSG:4326");
			$parsed_json = json_decode($json);
			$nbCapteurs = $parsed_json->{"totalFeatures"};

			// Récupération de la date et de l'heure via le capteur CAT17:
			// -----------------------------------------------------------
			$json_CAT17 = file_get_contents("https://data.mobility.brussels/bike/api/counts/?request=live&featureID=CAT17");
			$parsed_json_CAT17 = json_decode($json_CAT17);
			$date_jour = $parsed_json_CAT17->{"requestDate"};
		
			// Affichage de la première ligne du tableau:
			// ------------------------------------------
			echo "<p class=\"date\">Date & heure: ${date_jour}";
			echo "<p>";
			
			echo '<table border="1" width="95%" class="center">';
				echo '<caption>Fréquentation des vélos à Bruxelles: </caption>';
				echo "<br>";
					echo '<tr class="firstLine">';
						echo '<th scope="col">Lieu</th>';
						echo '<th scope="col">Capteur</th>';
						echo '<th scope="col">Tot. heure</th>';
						echo '<th scope="col">Tot. jour</th>';
						echo '<th scope="col">Tot. année</th>';
						echo '<th scope="col">Photo A</th>';
						echo '<th scope="col">Photo B</th>';
					echo '</tr>';

			// Décryptage des données provenant du fichier JSON et mise en forme pour leurs lectures dans la suite du tableau:
			// ---------------------------------------------------------------------------------------------------------------
			$json = file_get_contents("https://data.mobility.brussels/geoserver/bm_bike/wfs?service=wfs&version=1.1.0&request=GetFeature&typeName=bm_bike:rt_counting&outputFormat=json&srsName=EPSG:4326");
			$parsed_json = json_decode($json);
			$capteur = $parsed_json->{"features"};

			$nbrBikesHourTot = $nbrBikesDayTot = $nbrBikesYearTot = 0;		// Variables utilisées pour le stockage du nombre d'utilisateurs annuel
			for ($i = 0; $i < $nbCapteurs; $i++) {
				echo '<tr class="lignesDatas">';
					echo "<th scope=\"row\">"; echo ($capteur[$i]->{'properties'}->{"road_fr"}); echo "</th>";
					echo "<td>"; echo ($capteur[$i]->{'properties'}->{"device_name"}); echo "</td>";
					echo "<td>"; echo ($capteur[$i]->{'properties'}->{"hour_cnt"}); echo "</td>";
					echo "<td>"; echo ($capteur[$i]->{'properties'}->{"day_cnt"}); echo "</td>";
					echo "<td>"; echo ($capteur[$i]->{'properties'}->{"year_cnt"}); echo "</td>";
					echo "<td class=\"images\"><img src=\""; echo ($capteur[$i]->{'properties'}->{"pic_a"}); echo "\" height=\"150\" alt=\"---\"/></td>";
					echo "<td class=\"images\"><img src=\""; echo ($capteur[$i]->{'properties'}->{"pic_b"}); echo "\" height=\"150\" alt=\"---\"/></td>";
					$nbrBikesHourTot += ($capteur[$i]->{'properties'}->{"hour_cnt"});
					$nbrBikesDayTot += ($capteur[$i]->{'properties'}->{"day_cnt"});
					$nbrBikesYearTot += ($capteur[$i]->{'properties'}->{"year_cnt"});
				echo '</tr>';
			}
			echo '</table>';
			echo "<p class=\"grandTotal\">Totaux: $nbCapteurs capteurs | Heure en cours: $nbrBikesHourTot | Jour: $nbrBikesDayTot | Année: $nbrBikesYearTot";
		?>
	</body>

	<footer>&copy Nicolas - <?php echo date("Y"); ?> </footer>
</html>