<?php

require_once '../rockdb.php';
require_once '../page_pieces/navbar_rock.php';
require_once '../page_pieces/stylesAndScripts.php';

/*
$artistsMatchSpotifyMBID_Lookup = 'artistsMatchSpotifyMBID';
$artistListenersPlaycount = 'artistListenersPlaycount';
$albumListenersPlaycount = 'albumListenersPlaycount';
$trackListenersPlaycount = 'trackListenersPlaycount';
$relatedAlbums = 'relatedAlbums';
$relatedArtists = 'relatedArtists';
*/
$filenamesX = array (
    '../data_text/jsonLastFM/AliceCooper_Combined_03-06-19.json',
    '../data_text/jsonLastFM/Anvil_Group_03-06-19.json',
    '../data_text/jsonLastFM/BlackSabbath_Group_03-06-19.json',
    '../data_text/jsonLastFM/Dio_Group_03-06-19.json', 
    '../data_text/jsonLastFM/Elf_Group_03-06-19.json', 
    '../data_text/jsonLastFM/EvilStig_Group_03-06-19.json', 
    '../data_text/jsonLastFM/Heaven&Hell_Group_03-06-19.json', 
    '../data_text/jsonLastFM/JoanJett_Combined_03-06-19.json', 
    '../data_text/jsonLastFM/MeatLoaf_Person_03-06-19.json', 
    '../data_text/jsonLastFM/MötleyCrüe_Group_03-06-19.json', 
    '../data_text/jsonLastFM/OzzyOsbourne_Person_03-06-19.json', 
    '../data_text/jsonLastFM/Queen_Group_03-06-19.json', 
    '../data_text/jsonLastFM/QuietRiot_Group_03-06-19.json', 
    '../data_text/jsonLastFM/Rainbow_Group_03-06-19.json', 
    '../data_text/jsonLastFM/RonnieDioandtheProphets_Group_03-06-19.json', 
    '../data_text/jsonLastFM/RonnieDioandtheRedCaps_Group_03-06-19.json', 
    '../data_text/jsonLastFM/Saxon_Group_03-06-19.json', 
    '../data_text/jsonLastFM/Stoney&Meatloaf_Group_03-06-19.json',
    '../data_text/jsonLastFM/TedNugent_Person_03-06-19.json', 
    '../data_text/jsonLastFM/TheAmboyDukes_Group_03-06-19.json',
    '../data_text/jsonLastFM/TheElectricElves_Group_03-06-19.json', 
    '../data_text/jsonLastFM/TheRunaways_Group_03-06-19.json'
);


$x = ceil((count($filenames)));

for ($i=0; $i<$x; ++$i) {
    $jsonFile = $filenames[$i];
    $fileContents = file_get_contents($jsonFile);
    $artistData = json_decode($fileContents,true);
    
    $artistMBID = $artistData['mbid'];
    $artistName = $artistData['name'];
    
    $dataDate = $artistData['date'];
    
    $artistListeners = $artistData['stats']['listeners'];
    $artistPlaycount = $artistData['stats']['playcount'];
    
    echo $artistName . ' had ' . $artistListeners . ' listeners and ' . $artistPlaycount . ' plays on ' . $dataDate . '.<br>';
    
    $insertArtistStats = "INSERT INTO artistListenersPlaycount (artistMBID, dataDate, artistListeners, artistPlaycount) VALUES('$artistMBID','$dataDate','$artistListeners', '$artistPlaycount')";
    
    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
    
    if(!$connekt){
        echo 'Fiddlesticks! Could not connect to database.<br>';
    }
    
    $rockout = $connekt->query($insertArtistStats);
    
    if(!$rockout){
    echo 'Shickety Brickety! Could not insert stats for ' . $artistName . '.<br>';
    }
    else {
        echo ' Inserted ' . $artistListeners . ' listeners and ' . $artistPlaycount . ' plays for ' . $artistName . '.<br>';
    } 
    
};

?>