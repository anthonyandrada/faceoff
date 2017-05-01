# faceoff URL: faceoff.ddns.net

Schema used from upload.php:

CREATE TABLE VIDEO (
/n uuid uuid primary key,
/n  fileName char(24),
/n  frames INT,
/n  width INT,
/n  height INT,
/n  fps REAL,
/n  username char(24))
/n  processed BOOLEAN;

### To Do
- [x] Need to add processed column to DB

Need to create folders in src folder:
- [x] video
- [x] extractedFrames

Need to install php-pgsql to connect to db
- [x] sudo apt-get install php-pgsql

Need FFMPEG installed:
- [x] sudo apt-get install ffmpeg
