# faceoff URL: faceoff.ddns.net

Schema used from upload.php:

CREATE TABLE VIDEO (
/t uuid uuid primary key,
  fileName char(24),
  frames INT,
  width INT,
  height INT,
  fps REAL,
  username char(24))
  processed BOOLEAN;

### To Do
- [x] Need to add processed column to DB

Need to create folders in src folder:
- [x] video
- [x] extractedFrames

Need to install php-pgsql to connect to db
- [x] sudo apt-get install php-pgsql

Need FFMPEG installed:
- [x] sudo apt-get install ffmpeg
