<?php

function divideCombineArtists ($theseArtists) {
	
	$totalArtists = count($theseArtists);
	echo 'there are ' . $totalArtists . ' total artists<br>';

	// Divide all artists into chunks of 50
	$artistsChunk = array ();
	$x = ceil((count($theseArtists))/50);
	echo $totalArtists . ' divided by 50 is ' . $x . '<br>';

	$firstArtist = 0;

	for ($i=0; $i<$x; ++$i) {
		$lastArtist = 49;
		$artistsChunk = array_slice($theseArtists, $firstArtist, $lastArtist);
		// put chunks of 50 into an array
		$artistsArrays [] = $artistsChunk;
		$firstArtist += 50;
	};

	for ($i=0; $i<(count($artistsArrays)); ++$i) {

		$artistIds = implode(',', $artistsArrays[$i]);

		// For each array of artists (50 at a time), "get several artists"
		$bunchofartists = $GLOBALS['api']->getArtists($artistIds);
			
		foreach ($bunchofartists->artists as $artist) {
			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
			if(!$connekt){
				echo 'Fiddlesticks! Could not connect to database.<br>';
			}
			$artistID = $artist->id;
			$artistNameYucky = $artist->name;
			$artistName = mysqli_real_escape_string($connekt,$artistNameYucky);
			$artistArt = $artist->images[0]->url;
			$artistPop = $artist->popularity;
			$jsonArtistGenres = $artist->genres;

			$insertArtistsInfo = "INSERT INTO artists (artistID,artistName, artistArt) VALUES('$artistID','$artistName', '$artistArt')";
			$rockout = $connekt->query($insertArtistsInfo);
			if(!$rockout){
			echo 'Cursed-Crap. Could not insert artist ' . $artistName . '.<br>';
			}
	
			$insertArtistsPop = "INSERT INTO popArtists (artistID,pop) VALUES('$artistID','$artistPop')";
			$rockpop = $connekt->query($insertArtistsPop);
			if(!$rockpop){
				echo 'Cursed-Crap. Could not insert artists popularity.';
			}
	
			else {
				echo '<tr><td><img src="' . $artistArt . '"></td><td>' . $artistName . '</td><td>' . $artistPop . '</td></tr>';
			} 
			
		}
	};	
}

?>