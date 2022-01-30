<html>
	<head>
		<meta charset="utf-8" />
		<title>Vélos Bxl (avec images)</title>
        <style>
			body {
				font: 15px Courier, sans-serif;
			}

            p {
                color: blue;
				font-size: 18px;
            }

			table {
				border: solid;
				border-collapse: collapse;
				background-color: #A6F7EF;
				padding: 10px 0px 10px 0px;
			}
			
			caption {
				text-decoration: underline overline #FF3028;
                color: black;
				font-size: 24px;
				padding: 1px 1px 20px 1px;
            }

			.center {
				margin-left: auto;
				margin-right: auto;
			}

			tr, td, th{
				border: 1px solid green;
				text-align: right;
			}

			th {
				text-align: left;
			}

			.lignesDatas:hover {
				background-color: #E2B10E;
			}

			.under{
				text-decoration: underline #2487F7;
				font-size: 18px;
				background-color: yellow;
			}

			.grandTotal{
				text-decoration: underline #FF3028;
                color: black;
				font-size: 20px;
				font-weight: bold;
			}

			.date {
				text-decoration: blink;
			}

        </style>
	</head>
	<body>
		<a href="<?php $_SERVER['PHP_SELF']; ?>">Actualiser la page</a>
		
		<?php

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
					echo '<tr class="under">';
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

			$nbrBikesYearTot = 0;		// Variable utilisée pour le stockage du nombre d'utilisateurs annuel
			for ($i = 0; $i < 17; $i++) {
				echo '<tr class="lignesDatas">';
					echo "<th scope=\"row\">"; echo ($capteur[$i]->{'properties'}->{"road_fr"}); echo "</th>";
					echo "<td>"; echo ($capteur[$i]->{'properties'}->{"device_name"}); echo "</td>";
					echo "<td>"; echo ($capteur[$i]->{'properties'}->{"hour_cnt"}); echo "</td>";
					echo "<td>"; echo ($capteur[$i]->{'properties'}->{"day_cnt"}); echo "</td>";
					echo "<td>"; echo ($capteur[$i]->{'properties'}->{"year_cnt"}); echo "</td>";
					$nbrBikesYearTot = $nbrBikesYearTot + ($capteur[$i]->{'properties'}->{"year_cnt"});
					echo "<td><img src=\""; echo ($capteur[$i]->{'properties'}->{"pic_a"}); echo "\" height=\"150\" alt=\"Image A\"/></td>";
					echo "<td><img src=\""; echo ($capteur[$i]->{'properties'}->{"pic_b"}); echo "\" height=\"150\" alt=\"Image B\"/></td>";
				echo '</tr>';
			}
			echo '</table>';
			echo "<p class=\"grandTotal\">Grand total annuel: $nbrBikesYearTot";
		?>
	</body>
	<footer>&copy Nicolas - 2022</footer>
</html>