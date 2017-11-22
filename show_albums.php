<?php

session_start();

require 'vendor/autoload.php';
require_once 'navbar_rock.php';
require_once 'stylesAndScripts.php';
require_once 'albums9.php';

// Fetch saved access token
$accessToken = $_SESSION['accessToken'];

$GLOBALS['api'] = new SpotifyWebAPI\SpotifyWebAPI();
$GLOBALS['api']->setAccessToken($accessToken);

// could next line go in artist class?
$artistID = $_POST['artist'];

?>

<!DOCTYPE html><html>
<head><meta charset="UTF-8"><title>Ye Olde Album Results</title><?php echo $stylesAndSuch; ?></head>
<body>

<div class="container">

	<?php echo $navbar ?>
	
	<!-- main -->
	
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Albums</h3>
	</div>
	<div class="panel-body"> 
		
		<!-- Panel Content --> 

<?php
// echo "<h2>" . $artistName . "</h2>"; 
// echo "<p>" . $artistName . "'s popularity is " . $artistPop . ".</p>";
echo '<table class="table">';
echo '<tr><th>Artist Name</th><th>Album Name</th><th>Released</th><th>Popularity</th></tr>';

showAlbums ($artistID);

?>

</table>
</div> <!-- panel body -->

    </div> <!-- closing container -->
	<footer class="footer"><p>&copy; Sprout Means Grow and RoxorSoxor 2017</p></footer>
<?php echo $scriptsAndSuch; ?>

</body>
</html>