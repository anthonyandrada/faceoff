Schema used from upload.php:

CREATE TABLE VIDEO (
uuid uuid primary key,
fileName char(24),
frames INT,
width INT,
height INT,
fps REAL,
username char(24))
processed BOOLEAN;

Need to add processed column to DB:
[x]

Need to create folders in src folder:
[] video
[] extractedFrames

Need to install php-pgsql to connect to db
[x] sudo apt-get install php-pgsql

Need FFMPEG installed:
[x] sudo apt-get install ffmpeg
