<?php

require_once '../rockdb.php';
require( "class.artist.php" );

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect.';
};

// Rock en Espanol

$artistInfoRecentWithArt = "SELECT a.artistSpotID AS artistSpotID, a.artistArt AS artistArt, a.artistName AS artistName, p1.pop AS pop, p1.date AS date
    FROM artists a
    JOIN (SELECT p.*
			FROM popArtists p
			INNER JOIN (SELECT artistSpotID, pop, max(date) AS MaxDate
						FROM popArtists  
						GROUP BY artistSpotID) groupedp
			ON p.artistSpotID = groupedp.artistSpotID
			AND p.date = groupedp.MaxDate) p1
	ON a.artistSpotID = p1.artistSpotID
	WHERE a.artistSpotID IN ('0UKfenbZb15sqhfPC6zbt3', '4WquJweZPIK9qcfVFhTKvf', '0nJUwPwC9Ti4vvuJ0q3MfT', '1P8IfcNKwrkQP5xJWuhaOC', '2d0hyoQ5ynDBnkvAbJKORj', '0dmPX6ovclgOy8WWJaFEUU', '0Lpr5wXzWLtDWm1SjNbpPb', '1YLsqPcFg1rj7VvhfwnDWm')    
    ORDER BY a.artistName ASC";
    

$result = mysqli_query($connekt, $artistInfoRecentWithArt);

if (mysqli_num_rows($result) > 0) {
	$rows = array();
	while ($row = mysqli_fetch_array($result)) {
		$rows[] = $row;
	}
	echo json_encode($rows);
}

else {
	echo "Nope. Nothing to see here.";
}

mysqli_close($connekt);

?>