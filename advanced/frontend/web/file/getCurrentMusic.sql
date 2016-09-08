SELECT m.title, art.artist_name as content_lyric
                FROM tbl_music m
                LEFT JOIN tbl_musictoplay mtp ON m.id = mtp.id
                LEFT JOIN tbl_artist art ON m.artist_id = m.artist_id
                WHERE 1=1 AND mtp.user_id = 1  AND m.video_link = 'v6OuLldTq1U'  ORDER BY mtp.user_id ASC LIMIT 0,1