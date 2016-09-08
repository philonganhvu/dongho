SELECT m.title, art.artist_name as content_lyric
                FROM tbl_music m
                LEFT JOIN tbl_musictoplay mtp ON m.id = mtp.music_id
                LEFT JOIN tbl_artist art ON m.artist_id = m.artist_id
                WHERE mtp.status = 0 AND mtp.user_id = '1'  AND mtp.id >
                    (
                        SELECT m.id
                        FROM tbl_music m
                        LEFT JOIN tbl_musictoplay mtp ON m.id = mtp.id
                        WHERE mtp.status = 0
                                AND m.video_link = 'v6OuLldTq1U'
                    ) LIMIT 0,1