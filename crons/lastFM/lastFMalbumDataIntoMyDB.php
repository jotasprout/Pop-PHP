<?php

require_once '../../rockdb.php';

$filenames = array (
    'data/AliceCooper_Combined_04-24-19.json',
    'data/Anvil_Group_04-24-19.json',
    'data/BlackSabbath_Group_04-24-19.json',
    'data/LindseyBuckingham_Person_04-24-19.json',
    'data/DefLeppard_Group_04-24-19.json',
    'data/Dio_Group_04-24-19.json', 
    'data/Elf_Group_04-24-19.json', 
    'data/EvilStig_Group_04-24-19.json', 
    'data/FleetwoodMac_Group_04-24-19.json',
    'data/Heaven&Hell_Group_04-24-19.json', 
    'data/JanetJackson_Person_04-24-19.json', 
    'data/JoanJett_Combined_04-24-19.json', 
    'data/Journey_Group_04-24-19.json', 
    'data/MeatLoaf_Person_04-24-19.json', 
    'data/MötleyCrüe_Group_04-24-19.json', 
    'data/StevieNicks_Person_04-24-19.json',
    'data/OzzyOsbourne_Person_04-24-19.json', 
    'data/Queen_Group_04-24-19.json', 
    'data/QuietRiot_Group_04-24-19.json', 
    'data/Radiohead_Group_04-24-19.json',
    'data/Rainbow_Group_04-24-19.json', 
    'data/RonnieDioandtheProphets_Group_04-24-19.json', 
    'data/RonnieDioandtheRedCaps_Group_04-24-19.json', 
    'data/RoxyMusic_Group_04-24-19.json',
    'data/Saxon_Group_04-24-19.json', 
    'data/Stoney&Meatloaf_Group_04-24-19.json',
    'data/TedNugent_Person_04-24-19.json', 
    'data/TheAmboyDukes_Group_04-24-19.json',
    'data/TheCure_Group_04-24-19.json',
    'data/TheElectricElves_Group_04-24-19.json', 
    'data/TheRunaways_Group_04-24-19.json',
    'data/TheZombies_Group_04-24-19.json'
);

$filenames = $filenames;

$x = ceil((count($filenames)));

for ($i=0; $i<$x; ++$i) {

    $jsonFile = $filenames[$i];
    $fileContents = file_get_contents($jsonFile);
	
    $artistData = json_decode($fileContents,true);

    $artistMBID = $artistData['mbid'];
    $artistName = $artistData['name'];

    $dataDate = $artistData['date'];

    $albums = $artistData['albums'];

    $albumsNum = ceil((count($albums)));

    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

    if(!$connekt){
        echo 'Fiddlesticks! Could not connect to database.<br>';
    } else {

        for ($j=0; $j<$albumsNum; ++$j) {
            $album = $albums[$j];
            $releases = $album['releases'];
            $releasesNum = ceil((count($releases)));
            if ($releasesNum > 0){
                $releaseMBID = $album['releases'][0]['mbid'];
                $releaseNameYucky = $album['releases'][0]['name'];
                $releaseName = mysqli_real_escape_string($connekt,$releaseNameYucky);
                $albumListeners = $album['releases'][0]['listeners'];
                $albumPlaycount = $album['releases'][0]['playcount'];
				
				$insertLastFMalbumData = "INSERT INTO albumsLastFM (
					albumMBID, 
					dataDate,
					albumListeners,
					albumPlaycount
					) 
					VALUES(
						'$releaseMBID',
						'$dataDate',
						'$albumListeners',
						'$albumPlaycount'
					)";	
				
				$insertReleaseStats = $connekt->query($insertLastFMalbumData);
    
                if(!$insertReleaseStats){
                    echo '<p>Shickety Brickety! Could not insert ' . $releaseName . ' stats.</p>';
                } else {
                    echo '<p>' . $releaseName . ' had ' . $albumListeners . ' listeners and ' . $albumPlaycount . ' plays on ' . $dataDate . '.</p>';
                }
				
            }
        };
    };
};

?>