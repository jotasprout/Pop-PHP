<?php

require_once '../secrets/auth.php';
require_once '../vendor/autoload.php';
require_once '../rockdb.php';
require_once '../data_text/artists_arrays.php';
require_once '../functions/tracks.php';

$session = new SpotifyWebAPI\Session($myClientID, $myClientSecret);
$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();
$GLOBALS['api'] = new SpotifyWebAPI\SpotifyWebAPI();
$GLOBALS['api']->setAccessToken($accessToken);

function divideCombineAlbums ($artistAlbums) {
	
	// Divide all artist's albums into chunks of 20
	$artistAlbumsChunk = array ();
	$x = ceil((count($artistAlbums))/20);

	$firstAlbum = 0;
	
    for ($i=0; $i<$x; ++$i) {
	  $lastAlbum = 19;
	  $artistAlbumsChunk = array_slice($artistAlbums, $firstAlbum, $lastAlbum);
	  // put chunks of 20 into an array
      $albumsArrays [] = $artistAlbumsChunk;
      $firstAlbum += 20;
	};

	for ($i=0; $i<(count($albumsArrays)); ++$i) {
				
		$albumIds = implode(',', $albumsArrays[$i]);
	
		// For each array of albums (20 at a time), "get several albums"
		$bunchofalbums = $GLOBALS['api']->getAlbums($albumIds);
			
		foreach ($bunchofalbums->albums as $album) {

			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
	
			$albumID = $album->id;	
			$albumName = $album->name;
			$albumReleasedWhole = $album->release_date;
			$albumReleased = substr($albumReleasedWhole, 0, 4);
			$albumTotalTracks = intval($album->total_tracks);
			$thisArtistID = $album->artists[0]->id;
			$thisArtistName = $album->artists[0]->name;
			$albumPop = $album->popularity;
			$albumArt = $album->images[0]->url;

			$insertAlbum = "INSERT INTO albums (albumID,albumName,artistID,year,albumArt) VALUES('$albumID','$albumName','$thisArtistID','$albumReleased','$albumTotalTracks','$albumArt')";
			
			if (!$connekt) {
				echo '<p>Darn. Did not connect.</p>';
			};
			
			$rockout = $connekt->query($insertAlbum);
		
			if(!$rockout){
				echo '<p>Crap de General Tsao! Could not insert ' . $albumName . '.</p>';
			}
		
			$insertAlbumsPop = "INSERT INTO popAlbums (albumID,pop,date) VALUES('$albumID','$albumPop',curdate())";
		
			$rockin = $connekt->query($insertAlbumsPop);
			
			if(!$rockin){
				echo '<p>Sweet & Sour Crap! Could not insert albums popularity.</p>';
			}

            echo '<p><img src="' . $albumArt . '" height="64" width="64"><br>' . $albumName . '<br>' . $albumReleased . '<br><strong>Popularity:</strong> ' . $albumPop . '<br><strong>Total tracks:</strong> ' . $albumTotalTracks . '</p>';
			
			$AlbumsTracks = array ();
			
			$trackListOffset = 0;
			
			$d = ceil($albumTotalTracks/50);
			
			echo "<p>This album's tracks divided by 50 = " . $d . "</p>";
			
			for ($q=0; $q<$d; $q++) {
	
				$tracksChunk = array ();

				echo "<p>Tracklist Offset = " . $trackListOffset . ".</p>";

				echo "<p>Here is chunk #" . $q . ".</p>";
				
				$thisAlbumTracks = $GLOBALS['api']->getAlbumTracks($albumID, [
					'limit' => '50',
					'offset' => $trackListOffset,
					'market' => 'US'
				]);

				foreach ($thisAlbumTracks->items as $track) {
					$trackID = $track->id;
					$trackName = $track->name;
					$AlbumsTracks [] = $trackID;
				};

				$trackListOffset += 50;

				unset($tracksChunk);
			};
			
			divideCombineTracksAndInsertPop ($AlbumsTracks);

			unset($AlbumsTracks);

		}
	};
  
}

function gatherArtistAlbums ($artistID) {

	$discogOffset = 0;

	$discography = $GLOBALS['api']->getArtistAlbums($artistID, [
		'limit' => '50',
		'offset' => $discogOffset
	]);

	$artistAlbumsTotal = intval($discography->total);

	echo "<p>" . $artistID . " has " . $artistAlbumsTotal . " total albums.</p>";

	$a = ceil($artistAlbumsTotal/50);

	echo "<p>Total albums divided by 50 = " . $a;

	$allAlbumsThisArtist = array ();

	for ($p=0; $p<$a; $p++) {
		
		$discogChunk = array ();
		
		echo "<p>Discog Offset = " . $discogOffset . ".</p>";
		
		echo "<p>Here is chunk #" . $p . ".</p>";

		$discography = $GLOBALS['api']->getArtistAlbums($artistID, [
			'limit' => '50',
			'offset' => $discogOffset,
			'album_type' => ['album', 'compilation']//,
			//'market' => 'US'
		]);

		foreach ($discography->items as $album) {
			$albumID = $album->id;
			$discogChunk [] = $albumID;
		};

		$allAlbumsThisArtist = array_merge($allAlbumsThisArtist, $discogChunk);
		
		$discogOffset += 50;

		unset($discogChunk);
	};

	$thisMany = ceil(count($allAlbumsThisArtist));

	echo "I have gathered " . $thisMany . " albums total.";

	divideCombineAlbums ($allAlbumsThisArtist);

	unset($allAlbumsThisArtist);

}

function divideCombineArtistsForAlbums ($theseArtists) {

	// Divide all artists into chunks of 50
	$artistsChunk = array ();
	$x = ceil((count($theseArtists))/50);

	$firstArtist = 0;

	for ($i=0; $i<$x; ++$i) {
		$lastArtist = 49;
		$artistsChunk = array_slice($theseArtists, $firstArtist, $lastArtist);
		// put chunks of 50 into an array
		$artistsArraysArray [] = $artistsChunk;
		$firstArtist += 50;
	};

	for ($i=0; $i<(count($artistsArraysArray)); ++$i) {
		$artistsIds = implode(',', $artistsArraysArray[$i]);
		//echo '<br>these are the artist IDs ' . $artistsIds;
		$artistsArray = $artistsArraysArray[$i];

		// this next section experimental

		$bunchofartists = $GLOBALS['api']->getArtists($artistsArray);
			
		foreach ($bunchofartists->artists as $artist) {
			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
			if(!$connekt){
				echo '<p>Fiddlesticks! Could not connect to database.</p>';
			}
			$artistID = $artist->id;
			$artistNameYucky = $artist->name;
			$artistName = mysqli_real_escape_string($connekt,$artistNameYucky);
			$artistArt = $artist->images[0]->url;
			$artistPop = $artist->popularity;
			$artistFollowers = $artist->followers->total;
	
			$insertArtistsPop = "INSERT INTO popArtists (artistID,pop,followers,date) VALUES('$artistID','$artistPop','$artistFollowers',curdate())";

			$rockpop = $connekt->query($insertArtistsPop);
			if(!$rockpop){
				echo '<p>Cursed-Crap. Could not insert artists popularity & followers.</p>';
			}
	
			else {
				echo '<p><img src="' . $artistArt . '"></p><p>' . $artistName . '</p><p><b>Popularity:</b> ' . $artistPop . '</p><p><b>Followers:</b> ' . $artistFollowers . '</p>';
			} 
			
		}

		// previous section experimental
			
		for ($j=0; $j<(count($artistsArray)); ++$j) {

			$artistID = $artistsArray[$j];

			gatherArtistAlbums ($artistID);
			
		}
	};	

	unset($artistsChunk);

}

divideCombineArtistsForAlbums ($artists04);

?>