UPDATE tbl_musictoplay
                  SET status = 1
                  WHERE music_id = (
                      SELECT id
                      FROM tbl_music
                      WHERE video_link = 'S117qXZer7g'
                      LIMIT 0,1
                ) AND user_id = '1' 