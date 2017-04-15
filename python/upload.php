<?php
$alreadyExist = 0;
$correctExtension = 0;
$correctMimeType = 0;
$allowedTypes = array("mp4", "avi");
$fileName = basename($_FILES['filename']['name']);

//check file extension
$extension = pathinfo($fileName, PATHINFO_EXTENSION);
for($x = 0; $x < count($allowedTypes); $x++) {
    if(strcmp($allowedTypes[$x], $extension) == 0) {
        $correctExtension = 1;
    }
}

//connect to DB
$host        = "host=127.0.0.1";
$port        = "port=5432";
$dbname      = "dbname=cs160";
$credentials = "user=postgres password=asdf";
$db = pg_connect("$host $port $dbname $credentials");
//delete if statement after testing
if(!$db){
    echo "Connection to db failed!<br/>";
} else {
    echo "Connection to db Successful<br/>";
    echo "Filename: $fileName<br/>";
}

//Check if filename already in DB... QUERY NEEDS TO BE CHANGED
$query = "SELECT username FROM video WHERE username='$fileName'";
$result = pg_query($db, $query);
while($row = pg_fetch_row($result)) {
    if(strcmp($row[0], $fileName) !== 0) {
        echo "$row[0]" . "$fileName <br/>";
        $alreadyExist = 1;
    }
}

if($alreadyExist == 0) {
    $dir = $_SERVER['DOCUMENT_ROOT'] . "/video/";
    $finalDest = $dir . basename($_FILES['filename']['name']);
    echo "$finalDest<br/>";
    $tempName = $_FILES['filename']['tmp_name'];
    //check type of temp file
    $mimetype = mime_content_type($tempName);
    for($x = 0; $x < count($allowedTypes); $x++) {
        if(strcmp($allowedTypes[$x], $mimetype) == 0) {
            $correctMimeType = 1;
        }
    }
    //Save file to server if correct mime type
    if($correctMimeType = 1) {    
        $result = move_uploaded_file($tempName, $finalDest);
    }
    if($result) {
        echo "Upload successful!!";

    } else {
        echo "Upload failed!!";
    }
} else {
    echo "File name already exists in DB.";
}
//close connection and return to upload.html page.
pg_close($db);
header("location: http://localhost/upload.html");
?>
