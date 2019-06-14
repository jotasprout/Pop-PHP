<?php

$navbarIndex = "
<nav class='navbar navbar-default'>	
	<div id='header' class='container-fluid'>		
		<ul class='nav navbar-nav'>		
			
			<li><a href='https://roxorsoxor.com/poprock/index.php'>Artists List</a></li>
			<li><a href='https://roxorsoxor.com/poprock/multiArtists_albumsChart.php'>Group Chart</a></li>
			<li><a href='https://roxorsoxor.com/poprock/multiArtists_popTimeLines.php'>Compare Time</a></li>
			<li><a href='https://roxorsoxor.com/poprock/multiArtists_popCurrentColumns.php'>Compare Current Popularity</a></li>	
			<li><a href='https://roxorsoxor.com/poprock/multiArtists_followersCurrentColumns.php'>Compare Current Followers</a></li>
			<li><a href='https://roxorsoxor.com/poprock/genres.php'>Genres</a></li>	

		</ul>
	</div> <!-- /container-fluid -->   
</nav> <!-- /navbar -->
";

$navbar = "
<nav class='navbar navbar-default'>	
	<div id='header' class='container-fluid'>		
	<ul class='nav navbar-nav'>		

		<li><a href='https://roxorsoxor.com/poprock/index.php'>Artists List</a></li>
		<li><a href='https://roxorsoxor.com/poprock/artist_AlbumsListSpot.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID . "&source=spotify'>Albums Spotify</a></li>
		<li><a href='https://roxorsoxor.com/poprock/artist_AlbumsListLastFM.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID . "&source=musicbrainz'>Albums LastFM</a></li>
		<li><a href='https://roxorsoxor.com/poprock/artist_TracksListSpot.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID . "&source=spotify'>Tracks Spotify</a></li>
		<li><a href='https://roxorsoxor.com/poprock/artist_TracksListLastFM.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID . "&source=musicbrainz'>Tracks LastFM</a></li>
		<li><a href='https://roxorsoxor.com/poprock/multiArtists_albumsChart.php'>Group Chart</a></li>
		<li><a href='https://roxorsoxor.com/poprock/multiArtists_popTimeLines.php'>Compare Time</a></li>
		<li><a href='https://roxorsoxor.com/poprock/multiArtists_popCurrentColumns.php'>Compare Current Popularity</a></li>	
		<li><a href='https://roxorsoxor.com/poprock/multiArtists_followersCurrentColumns.php'>Compare Current Followers</a></li>
		<li><a href='https://roxorsoxor.com/poprock/genres.php'>Genres</a></li>	

	</ul>
	</div> <!-- /container-fluid -->   
</nav> <!-- /navbar -->
";

?>