<?php

include 'page_pieces/sesh.php';
require_once 'rockdb.php';
require_once 'page_pieces/navbar_rock.php';
require_once 'page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
};

$artistInfoWithArtAndGenres = "SELECT a.artistSpotID, a.artistArt, a.artistName, g.genre
    FROM artists a
    JOIN genres g ON a.artistSpotID = g.artistSpotID
	ORDER BY a.artistName ASC";

$getit = $connekt->query( $artistInfoWithArtAndGenres );

if(!$getit){ 
	echo 'Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($getit) . '</p>';
}	

?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="UTF-8">
	<title>Genres</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>

	<div class="container">
		<?php echo $navbar ?>

		<!-- main -->

		<div class="panel panel-primary">

			<div class="panel-heading">
				<h3 class="panel-title">Click a genre to compare artists in that genre.</h3>
			</div>

			<div class="panel-body">

				<!-- Panel Content -->
				<!-- D3 chart goes here -->
				<?php if (!empty($getit)) { ?>

				<table class="table" id="tableoartists">
				<thead>
					<tr>
						<!--
					<th>Pretty Face</th>	
						-->
					<th onClick="sortColumn('artistName', 'DESC')"><div class="pointyHead">Artist Name</div></th>
					<th onClick="sortColumn('genre', 'ASC')"><div class="pointyHead">Genre</div></th>
					</tr>
				</thead>

				<tbody>

					<?php
						while ( $row = mysqli_fetch_array( $getit ) ) {
							$artistName = $row[ "artistName" ];
							$artistGenre = $row[ "genre" ];
							$artistArt = $row[ "artistArt" ];
					?>

					<tr>
						<!--
						<td><img src='<?php // echo $artistArt ?>' height='64' width='64'></td>	
						-->
						<td><?php echo $artistName ?></td>
						<td><a href='https://www.roxorsoxor.com/poprock/genreArtists_popCurrentBars.php?artistGenre=<?php echo $artistGenre ?>'><?php echo $artistGenre ?></a></td>
					</tr>

					<?php 
						} // end of while
					?>

					</tbody>
				</table>
				<?php 
					} // end of if
				?>

			</div>
			<!-- panel body -->

		</div>
		<!-- panel panel-primary -->

	</div>
	<!-- close container -->

	<?php echo $scriptsAndSuch; ?>
	<script src="https://www.roxorsoxor.com/poprock/functions/sort_genresArtists.js"></script>

	<script>
	
	</script>

</body>

</html>