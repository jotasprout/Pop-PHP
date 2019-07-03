<?php

require_once 'rockdb.php';

require_once 'page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect.';
};

$allthatAndLastFM = "SELECT a.artistMBID AS artistMBID, s.artistSpotID, a.artistArtMB AS artistArt, a.artistNameMB AS artistNameMB, f1.dataDate AS dataDate, f1.artistListeners AS artistListeners, f1.artistPlaycount AS artistPlaycount, f1.dataDate AS date
    FROM artistsMB a
	LEFT JOIN (SELECT f.*
			FROM artistsLastFM f
			INNER JOIN (SELECT artistMBID, artistListeners, artistPlaycount, max(dataDate) AS MaxDataDate
						FROM artistsLastFM  
						GROUP BY artistMBID) groupedf
			ON f.artistMBID = groupedf.artistMBID
			AND f.dataDate = groupedf.MaxDataDate) f1
	ON a.artistMBID = f1.artistMBID
	LEFT JOIN artistsSpot s ON s.artistMBID = a.artistMBID
	ORDER BY a.artistNameMB ASC;";

$getit = $connekt->query( $allthatAndLastFM );

if(!$getit){ echo 'Cursed-Crap. Did not run the query.'; }	

?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="UTF-8">
	<title>All LastFM-Only Artists</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>

	<div class='container-fluid'>
	
	<div id="fluidCon">
	</div> <!-- end of fluidCon -->
	<!-- main -->

	<div class="panel panel-primary">

		<div class="panel-heading">
			<h3 class="panel-title">LastFM Data for Artists</h3>
		</div>

		<div class="panel-body">

			<!-- Panel Content -->
			<?php if (!empty($getit)) { ?>

<table class="table" id="tableoartists">
	<thead>
<tr>
	<th>Pretty Face</th>	
	<th onClick="sortColumn('artistNameMB', 'ASC')"><div class="pointyHead">Artist Name</div></th>
	<th>MBID</th>

	<th onClick="sortColumn('datadate', 'unsorted')"><div class="pointyHead popStyle">LastFM<br>Data Date</div></th>			

	<th onClick="sortColumn('artistListeners', 'unsorted')"><div class="pointyHead rightNum">LastFM<br>Listeners</div></th>
	<th onClick="sortColumn('artistPlaycount', 'unsorted')"><div class="pointyHead rightNum">LastFM<br>Playcount</div></th>
	<th><div class="popStyle">LastFM<br>Ratio</div></th>
</tr>
</thead>

<tbody>

<?php
	while ( $row = mysqli_fetch_array( $getit ) ) {
		$artistNameMB = $row[ "artistNameMB" ];

		$artistMBID = $row[ "artistMBID" ];
		$artistSpotID = $row[ "artistSpotID" ];
		$artistArt = $row[ "artistArt" ];

		$lastFMDate = $row[ "dataDate" ];
		$artistListenersNum = $row[ "artistListeners"];
		$artistListeners = number_format ($artistListenersNum);

		$artistPlaycountNum = $row[ "artistPlaycount"];
		$artistPlaycount = number_format ($artistPlaycountNum);
		$playsPerListener = 0;
		if ($artistPlaycount != 0) {
			$playsPerListener = floor($artistPlaycountNum/$artistListenersNum);
		};
		$artistRatio = "1:" . $playsPerListener;
		if ($artistListeners == 0){
			$artistRatio = "0:" . $playsPerListener;
		}
?>

<tr>
	<td><a href='https://www.roxorsoxor.com/poprock/artist_ChartsLastFM.php?artistMBID=<?php echo $artistMBID ?>&artistSpotID=<?php echo $artistSpotID ?>'><img src='<?php echo $artistArt ?>' class="indexArtistArt"></a></td>	
	
	<td><a href='https://www.roxorsoxor.com/poprock/artist_ChartsLastFM.php?artistMBID=<?php echo $artistMBID ?>&artistSpotID=<?php echo $artistSpotID ?>'><?php echo $artistNameMB ?></a></td>

	<td><?php echo $artistMBID ?></td>
	<!-- --> 
	<td class="popStyle"><?php echo $lastFMDate ?></td>		
	<td class="rightNum"><?php echo $artistListeners ?></td>
	<td class="rightNum"><?php echo $artistPlaycount ?></td>
	<td class="popStyle"><?php echo $artistRatio ?></td>
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
	<script src="https://www.roxorsoxor.com/poprock/functions/sort_ArtistsLastFM.js"></script>
	<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbarIndex.js"></script>

</body>

</html>