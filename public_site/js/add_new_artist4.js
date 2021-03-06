const artistForm = document.getElementById('addartist').addEventListener('submit', submitArtist);  


function submitArtist (event) {
    event.preventDefault();
    let artist = document.getElementById('artist').value;
    sendArtistToServer (artist);
}; // end of submitArtist

function sendArtistToServer (artist) {
    const url = 'functions/add_new_artist.php';
    const artistToSend = {
        artist
    };  
    const artistOptions = {
        method: 'POST',
        body: JSON.stringify(artistToSend),
        headers: {
        'Content-Type': 'application/json'
        }
    };
    fetch(url, artistOptions).then(response => response.json())
    .then(json => {
        console.log(json);
        console.log("this artist is " + json.name);

        const table = document.getElementById('artistInfo');
        const tr1 = document.createElement('tr');
        const td1 = document.createElement('td');
        const image = document.createElement('img');
        const imageURL = json.images[0].url;
        image.src = imageURL;
        td1.append(image);
        tr1.append(td1);
        table.append(tr1);
        const tr2 = document.createElement('tr');
        const td2 = document.createElement('td');
        td2.innerHTML = json.name;
        tr2.append(td2);
        table.append(tr2);
        const tr3 = document.createElement('tr');
        const td3 = document.createElement('td');
        td3.innerHTML = json.popularity;
        tr3.append(td3);
        table.append(tr3);

    }).catch(err => {
        console.log (err);
    });


/*
   fetch(url, artistOptions).then(response => {
      return response.json();
   }).then(data => {
       console.log(data);
       console.log("this artist is " + data.name);
   }).catch(err => {
       console.log (err);
   });
*/

}