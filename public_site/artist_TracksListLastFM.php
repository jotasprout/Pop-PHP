<?php

$artistSpotID = $_GET['artistSpotID'];
$artistMBID = $_GET['artistMBID'];
$source = $_GET['source'];

require_once 'rockdb.php';
require_once 'page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect.';
};

$blackSabbath_MBID = '5182c1d9-c7d2-4dad-afa0-ccfeada921a8';

$gatherTrackInfo = "SELECT v.artistNameMB, v.trackNameMB, v.trackNumber, v.albumNameMB, v.trackListeners, v.trackPlaycount, max(v.dataDate) AS MaxDataDate
					FROM (
						SELECT z.trackMBID, z.trackNameMB, z.trackNumber, z.albumNameMB, z.artistNameMB, p.dataDate, p.trackListeners, p.trackPlaycount
							FROM (
								SELECT t.*, r.albumNameMB, a.artistNameMB
									FROM tracksMB t
									INNER JOIN albumsMB r ON r.albumMBID = t.albumMBID
									JOIN artistsMB a ON r.artistMBID = a.artistMBID
									WHERE a.artistMBID = '$artistMBID'
							) z
						JOIN tracksLastFM p 
							ON z.trackMBID = p.trackMBID					
					) v
					GROUP BY v.trackMBID
					ORDER BY v.trackPlaycount DESC";


$getit = $connekt->query( $gatherTrackInfo );

if ( !$getit ) {
	echo 'Cursed-Crap. Did not run the query.';
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Stats for All Tracks By This Artist</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>

	<div class="container-fluid">

	<div id="fluidCon">
</div> <!-- end of fluidCon -->

		<!-- main -->
		<p>If this page is empty, or the wrong discography displays, <a href='https://www.roxorsoxor.com/poprock/indexLastFM.php'>choose an artist</a> first.</p>

		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 id="panelTitle" class="panel-title">Latest Tracks Stats from My Database</h3>
			</div>
			<div class="panel-body">

				<?php if(!empty($getit)) { ?>
				
<table class="table" id="tableotracks">
<thead>
<tr>
<!---->
<th onClick="sortColumn('albumNameMB', 'DESC', '<?php echo $artistMBID ?>', '<?php echo $source ?>')"><div class="pointyHead">Album Title</div></th>

<th onClick="sortColumn('trackNameMB', 'ASC', '<?php echo $artistMBID ?>', '<?php echo $source ?>')"><div class="pointyHead">Track Title</div></th>
<!--
<th class="popStyle">Track #</th>
<th class="popStyle">LastFM<br>Data Date</th>
-->
<th class="rightNum pointyHead" onClick="sortColumn('trackListeners', '<?php echo $popNewOrder; ?>', '<?php echo $artistMBID ?>', '<?php echo $source ?>')"">LastFM<br>Listeners</th>
<th class="rightNum pointyHead" onClick="sortColumn('trackPlaycount', '<?php echo $popNewOrder; ?>', '<?php echo $artistMBID ?>', '<?php echo $source ?>')">LastFM<br>Playcount</th>
<th><div class="popStyle">LastFM<br>Ratio</div></th>
</tr>
</thead>
					
					<tbody>
					<?php
						while ( $row = mysqli_fetch_array( $getit ) ) {
							$albumNameMB = $row[ "albumNameMB" ];
							$trackNameMB = $row[ "trackNameMB" ];
							$trackNumber = $row[ "trackNumber" ];
							$artistNameMB = $row[ "artistNameMB" ];
							$lastFMDate = $row[ "MaxDataDate" ];
							$trackListenersNum = $row["trackListeners"];
							$trackListeners = number_format ($trackListenersNum);
							$trackPlaycountNum = $row["trackPlaycount"];
							$trackPlaycount = number_format ($trackPlaycountNum);
							$playsPerListener = 0;
							if ($trackPlaycount != 0) {
								$playsPerListener = floor($trackPlaycountNum/$trackListenersNum);
							};
							$trackRatio = "1:" . $playsPerListener;
							if ($trackListeners == 0){
								$trackRatio = "0:" . $playsPerListener;
							}
					?>
							<tr>
								
								<td><?php echo $albumNameMB ?></td>
								<td><?php echo $trackNameMB ?></td>
                                <!--
								<td><?php //echo $trackNumber ?></td>
								<td class="popStyle"><?php //echo $lastFMDate ?></td>
                                -->
								<td class="rightNum"><?php echo $trackListeners ?></td>
								<td class="rightNum"><?php echo $trackPlaycount ?></td>
								<td class="popStyle"><?php echo $trackRatio ?></td>
							</tr>
					<?php 
						} // end of while
					?>
					
					</tbody>
				</table>
				<?php 
					} // end of if
				?>
			</div> <!-- panel body -->
		</div> <!-- panel panel-primary -->
	</div> <!-- closing container -->
	
<?php echo $scriptsAndSuch; ?>

<script>
	const artistNameMB = '<?php echo $artistNameMB; ?>';
	const panelTitleText = 'Last.fm stats for all tracks by ' + artistNameMB;
	const panelTitle = document.getElementById('panelTitle');
	const docTitleText = 'All ' + artistNameMB + ' tracks Last.fm Stats';
	$(document).ready(function(){
		panelTitle.innerHTML = panelTitleText;
		document.title = docTitleText;
	});
</script>

<script>
	const artistSpotID = '<?php echo $artistSpotID ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>

<script src="https://www.roxorsoxor.com/poprock/functions/sort_artistTracksLastFM.js"></script>
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>
	
</html>