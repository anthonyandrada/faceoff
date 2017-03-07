import ffmpegHelper, psycopg2, uuid

def createVideo(username):
        uid = uuid.uuid5(uuid.NAMESPACE_DNS, 'faceoff.ddns.net')
        md = getMetadata("/path/to/video")
        with psycopg2.connect("dbname='cs160' user='postgres' host='localhost' password='student'") as conn:
            with conn.cursor() as curs:
                curs.execute("INSERT INTO video(uuid, frames, width, height, fps, username) VALUES (%s, %s, %s, %s, %s, %s)", (uid, md[2], md[1], md[0], md[4], username))
                curs.execute("SELECT video_id FROM video WHERE uuid = %s", (uid,))
                result = curs.fetchone()
                
        extractFrames("/path/to/video", uid, result[0])
        return
