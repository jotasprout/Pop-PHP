<?php

$artistID = $_GET['artistID'];

//$artistID = '0cc6vw3VN8YlIcvr1v7tBL';

require_once '../rockdb.php';
require( "class.artist.php" );

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo 'Darn. Did not connect.';
};

// Could I remove artistID from the select statement below as long as artistID is in the WHERE clause?
$artistInfoAll = "SELECT a.artistID, a.artistName, a.artistArt, b.followers, b.date
	FROM artists a
		INNER JOIN popArtists b ON a.artistID = b.artistID
			WHERE a.artistID = '$artistID'
				AND b.followers IS NOT NULL
	ORDER BY b.date DESC";

$getit = mysqli_query($connekt, $artistInfoAll);

if(!$getit){
	echo 'Cursed-Crap. Did not run the query.';
}	

if (mysqli_num_rows($getit) > 0) {
	$rows = array();
	while ($row = mysqli_fetch_array($getit)) {
		$rows[] = $row;
	}
	echo json_encode($rows);
}

else {
	echo "Nope. Nothing to see here.";
}

mysqli_close($connekt);

?>