<?php

$albumsTracksArrays = array ();

function divideCombineTracksAndInsertPop ($allArtistTracks) {

	$thisMany4 = ceil(count($allArtistTracks));

	echo "I have gathered " . $thisMany4 . " tracks from this artist.";
	
	// Divide all artist's tracks into chunks of 50
	$tracksChunk = array ();
	$x = ceil((count($allArtistTracks))/50);

	$firstTrack = 0;

	for ($i=0; $i<$x; ++$i) {
		$lastTrack = 49;
		$tracksChunk = array_slice($allArtistTracks, $firstTrack, $lastTrack);
		// put chunks of 50 into an array
		$albumsTracksArrays [] = $tracksChunk;
		$firstTrack += 50;
	};

	for ($i=0; $i<(count($albumsTracksArrays)); ++$i) {
				
		$tracksThisTime = count($albumsTracksArrays[$i]);
		// echo $tracksThisTime . '<br>';

		$trackSpotIDs = implode(',', $albumsTracksArrays[$i]);

		// For each array of tracks (50 at a time), "get several tracks"
		$bunchoftracks = $GLOBALS['api']->getTracks($trackSpotIDs);
			
		foreach ($bunchoftracks->tracks as $track) {
			
			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
			
			if (!$connekt) {
				echo 'Darn. Did not connect.';
			};			

			$trackSpotID = $track->id;
			$trackAlbumNameSpot = $track->album->name;
			$trackNameSpot = $track->name;
			$trackNumber = $track->track_number;
			$trackPop = $track->popularity;

			echo "<p>" . $trackNameSpot . " is track #" . $trackNumber . "</p>";
	
			$insertTrackPop = "INSERT INTO popTracks (trackSpotID,pop,date) VALUES('$trackSpotID','$trackPop',curdate())";
	
			$rockpop = $connekt->query($insertTrackPop);
			
			if(!$rockpop){
				echo '<p>Confounded-Crap. Could not insert POPULARITY for "' . $trackNameSpot . '" from <i>' . $trackAlbumNameSpot . '</i>.</p>';
			}
	
			else {
				echo "<p>" . $trackNameSpot . " from <i>" . $trackAlbumNameSpot . "</i>" . " has pop " . $trackPop . "</p>";
			}

			$insertTrackNumber = "UPDATE tracksSpot SET trackNumber = $trackNumber WHERE trackSpotID = '$trackSpotID'";
	
			$rockNum = $connekt->query($insertTrackNumber);

			if(!$rockNum){
				echo '<p>Confounded-Crap. Could not update TRACK # for "' . $trackNameSpot . '" from <i>' . $trackAlbumNameSpot . '</i>. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
			}
	
			else {
				echo "<p>" . $trackNameSpot . " is track #" . $trackNumber . " on <i>" . $trackAlbumNameSpot . "</i>.</p>";
			}
		}
	};
}


?>