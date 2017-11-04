<?php
// require_once 'tracks6.php';

$artistAlbums = array ();

function divideCombineAlbumsForTracks ($artistAlbums) {

	$albumsArrays = array ();
	
	// Divide all artist's albums into chunks of 20
	$artistAlbumsChunk = array ();
	$x = ceil((count($artistAlbums))/20);

	$firstAlbum = 0;
	
    for ($i=0; $i<$x; ++$i) {
	  $lastAlbum = $firstAlbum + 19;
      $artistAlbumsChunk = array_slice($artistAlbums, $firstAlbum, $lastAlbum);
	  // put chunks of 20 into an array
      $albumsArrays [] = $artistAlbumsChunk;
      $firstAlbum += 19;
	};

	for ($i=0; $i<(count($albumsArrays)); ++$i) {
				
		$albumIds = implode(',', $albumsArrays[$i]);
	
		// For each array of albums (20 at a time), "get several albums"
		$bunchofalbums = $GLOBALS['api']->getAlbums($albumIds);
			
		foreach ($bunchofalbums->albums as $album) {

			$AlbumsTracks = array();
	
			$albumID = $album->id;
			
			$thisAlbumTracks = $GLOBALS['api']->getAlbumTracks($albumID);

			// should be method in albums class
			foreach ($thisAlbumTracks->items as $track) {
				
				// Get each trackID for requesting Full Track Object with popularity
				$trackID = $track->id;
				
				// Put trackIDs in array for requesting several at a time (far fewer requests)
				$AlbumsTracks [] = $trackID;
				
			}

			divideCombineTracks ($AlbumsTracks);
			unset($AlbumsTracks);
			
		}
		
	};
	
}


function divideCombineAlbums ($artistAlbums) {
	
	// Divide all artist's albums into chunks of 20
	$artistAlbumsChunk = array ();
	$x = ceil((count($artistAlbums))/20);

	$firstAlbum = 0;
	
    for ($i=0; $i<$x; ++$i) {
	  $lastAlbum = $firstAlbum + 19;
      $artistAlbumsChunk = array_slice($artistAlbums, $firstAlbum, $lastAlbum);
	  // put chunks of 20 into an array
      $albumsArrays [] = $artistAlbumsChunk;
      $firstAlbum += 19;
	};

	for ($i=0; $i<(count($albumsArrays)); ++$i) {
				
		$albumIds = implode(',', $albumsArrays[$i]);
	
		// For each array of albums (20 at a time), "get several albums"
		$bunchofalbums = $GLOBALS['api']->getAlbums($albumIds);
			
		foreach ($bunchofalbums->albums as $album) {
	
			$albumID = $album->id;
			$albumName = $album->name;
			$albumReleasedWhole = $album->release_date;
			$albumReleased = substr($albumReleasedWhole, 0, 4);
			$albumPop = $album->popularity;
			$thisArtistName = $album->artists[0]->name;
		
			echo '<tr><td>' . $albumName . '</td><td>' . $albumReleased . '</td><td>' . $albumPop . '</td></tr>';
		}
	};
  
}

?>