SELECT v.trackName, v.albumName, v.pop, max(v.date) AS MaxDate
	FROM (
		SELECT z.trackSpotID, z.trackName, r.albumName, p.date, p.pop
			FROM (
				SELECT t.trackSpotID, t.trackName, t.albumSpotID
					FROM tracks t
					WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
			) z
		INNER JOIN albums r 
			ON r.albumSpotID = z.albumSpotID
		JOIN popTracks p 
			ON z.trackSpotID = p.trackSpotID					
	) v
	GROUP BY v.trackSpotID

/*
Now let us filter to current day -- HOLY SHIT IT'S FAST!
*/

SELECT d.trackName, d.albumName, d.trackListeners, d.trackPlaycount, max(d.dataDate) AS MaxDataDate
	FROM (
		SELECT k.trackMBID, k.trackName, h.albumName, fm.dataDate, fm.trackListeners, fm.trackPlaycount
			FROM (
				SELECT m.trackMBID, m.trackName, m.albumMBID
					FROM tracksMB m
					WHERE m.albumMBID = '5d2e8936-8c36-3ccd-8e8f-916e3b771d49'
			) k
			INNER JOIN albumsMB h
				ON h.albumMBID = k.albumMBID
			JOIN tracksLastFM fm
				ON fm.trackMBID = k.trackMBID
	) d
	GROUP BY d.trackMBID

/*	

/*
below works for MBID album and tracks!
*/
SELECT k.trackMBID, k.trackName, h.albumName
	FROM (
		SELECT m.trackMBID, m.trackName, m.albumMBID
			FROM tracksMB m
			WHERE m.albumMBID = '5d2e8936-8c36-3ccd-8e8f-916e3b771d49'
	) k
	INNER JOIN albumsMB h
		ON h.albumMBID = k.albumMBID;
/*
adding LastFM data WORKS!
*/
SELECT k.trackMBID, k.trackName, h.albumName, fm.dataDate, fm.trackListeners, fm.trackPlaycount
	FROM (
		SELECT m.trackMBID, m.trackName, m.albumMBID
			FROM tracksMB m
			WHERE m.albumMBID = '5d2e8936-8c36-3ccd-8e8f-916e3b771d49'
	) k
	INNER JOIN albumsMB h
		ON h.albumMBID = k.albumMBID
    JOIN tracksLastFM fm
    	ON fm.trackMBID = k.trackMBID
/*
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 
tried this and it worked
*/
SELECT z.trackName 
FROM (
	SELECT t.trackSpotID, t.trackName
	FROM tracks t
	WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
) z

/* 
Get trackName, trackSpotID, albumName -- worked. Tiny bit slower
*/

SELECT t.trackSpotID, t.trackName, r.albumName
FROM tracks t
INNER JOIN albums r ON r.albumSpotID = t.albumSpotID
WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'

/* 
That worked great so adding unfiltered popularity AND IT WORKS AND FAST!
*/
SELECT z.trackName, p.date, p.pop
FROM (
	SELECT t.trackSpotID, t.trackName
	FROM tracks t
	WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
) z
JOIN popTracks p ON z.trackSpotID = p.trackSpotID
ORDER BY z.trackName ASC, p.date DESC

/* 
That worked great so adding unfiltered popularity AND IT WORKS AND FAST but a little slower than below (without albumName)!
*/
SELECT z.trackName, r.albumName, p.date, p.pop
FROM (
	SELECT t.trackSpotID, t.trackName, t.albumSpotID
	FROM tracks t
	WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
) z
INNER JOIN albums r ON r.albumSpotID = z.albumSpotID
JOIN popTracks p ON z.trackSpotID = p.trackSpotID
ORDER BY z.trackName ASC, p.date DESC


/* 
Below is "popEvil" 
*/
SELECT z.trackName, r.albumName, p.date, p.pop
FROM (
	SELECT t.trackSpotID, t.trackName, t.albumSpotID
	FROM tracks t
	WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
) z
INNER JOIN albums r ON r.albumSpotID = z.albumSpotID
JOIN popTracks p ON z.trackSpotID = p.trackSpotID


/* 
From clipboard didn't really work but ...
*/
SELECT p1.* 
	FROM (
		SELECT e.* 
		FROM (
			SELECT z.trackSpotID, z.trackName, r.albumName, p.date, p.pop
				FROM (
					SELECT t.trackSpotID, t.trackName, t.albumSpotID
						FROM tracks t
						WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
				) z
			INNER JOIN albums r 
				ON r.albumSpotID = z.albumSpotID
			JOIN popTracks p 
				ON z.trackSpotID = p.trackSpotID
		) e
		INNER JOIN (
			SELECT v.trackSpotID AS trackSpotID, v.pop, max(v.date) AS MaxDate
				FROM (
					SELECT z.trackSpotID AS trackSpotID, z.trackName, r.albumName, p.date, p.pop
						FROM (
							SELECT t.trackSpotID AS trackSpotID, t.trackName, t.albumSpotID
								FROM tracks t
								WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
						) z
					INNER JOIN albums r 
						ON r.albumSpotID = z.albumSpotID
					JOIN popTracks p 
						ON z.trackSpotID = p.trackSpotID					
				) v
				GROUP BY v.trackSpotID
		) groupde
			ON e.trackSpotID = groupede.trackSpotID
			AND e.date = groupde.MaxDate
	) p1



/* 
THIS WORKS! YAY!
*/
SELECT p1.* 
	FROM (
		SELECT e.* 
		FROM (
			SELECT z.trackSpotID, z.trackName, r.albumName, p.date, p.pop
				FROM (
					SELECT t.trackSpotID, t.trackName, t.albumSpotID
						FROM tracks t
						WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
				) z
			INNER JOIN albums r 
				ON r.albumSpotID = z.albumSpotID
			JOIN popTracks p 
				ON z.trackSpotID = p.trackSpotID
		) e
		INNER JOIN (

		) groupde
			ON e.trackSpotID = groupede.trackSpotID
			AND e.date = groupde.MaxDate
	) p1

/* 
From clipboard ... but this might
*/
SELECT v.trackName, v.albumName, v.pop, max(v.date) AS MaxDate
	FROM (
		SELECT z.trackSpotID, z.trackName, r.albumName, p.date, p.pop
			FROM (
				SELECT t.trackSpotID, t.trackName, t.albumSpotID
					FROM tracks t
					WHERE t.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
			) z
		INNER JOIN albums r 
			ON r.albumSpotID = z.albumSpotID
		JOIN popTracks p 
			ON z.trackSpotID = p.trackSpotID					
	) v
	GROUP BY v.trackSpotID
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
/* 
Cutting lastFM stuff from current query - Also works AND also really super slow
*/

SELECT t.trackSpotID, t.trackName, a.albumName, a.artistSpotID, p1.pop, p1.date
FROM tracks t
INNER JOIN albums a ON a.albumSpotID = t.albumSpotID
JOIN (SELECT p.* FROM popTracks p
		INNER JOIN (SELECT trackSpotID, pop, max(date) AS MaxDate
					FROM popTracks  
					GROUP BY trackSpotID) groupedp
		ON p.trackSpotID = groupedp.trackSpotID
		AND p.date = groupedp.MaxDate) p1 
ON t.trackSpotID = p1.trackSpotID
WHERE a.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
ORDER BY p1.pop DESC;

/* 
Next one works but it is really super slow
*/

SELECT groupedp.trackSpotID, groupedp.pop, groupedp.MaxDate, t1.trackName
FROM (SELECT p0.trackSpotID, p0.pop, max(p0.date) AS MaxDate
FROM popTracks p0 
GROUP BY p0.trackSpotID) groupedp
JOIN tracks t1
ON groupedp.trackSpotID = t1.trackSpotID
WHERE t1.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'