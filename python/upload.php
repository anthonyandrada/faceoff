<?php
//variables
$fileName = basename($_FILES['filename']['name']);

//check extension that was provided before actually uploading the file
$correctExtension = checkExtension($fileName);
//check to see if filename already exists in the database
$alreadyExist = checkAlreadyExist($fileName);
//continue is filename is not in database
if($alreadyExist == 0) {
    $dir = $_SERVER['DOCUMENT_ROOT'] . "/video/";
    $finalDest = $dir . basename($_FILES['filename']['name']);
    $tempName = $_FILES['filename']['tmp_name'];
    //actually check if the file is a video
    $mimetype = mime_content_type($tempName);
    $correctMimeType = checkMimeType($mimetype);
    //Save file to server if correct mime type
    if($correctMimeType == 1) {
        $result = move_uploaded_file($tempName, $finalDest);
    }
    if($result) {
        echo "Upload successful!!";
        //RUN FFMPEG ON THE FILE
    } else {
        echo "Upload failed!!";
    }
} else {
    echo "File name already exists in DB.";
}

//------------------------ FUNCTIONS BELOW ------------------------
function checkMimeType($mimetype) {
    $allowedTypes = array("mp4", "avi");
    //split string into 2 parts: file/mime type and extension
    $part = explode("/", $mimetype);
    if(strcmp($part[0],"video") ==0) {
        for($x = 0; $x < count($allowedTypes); $x++) {
            if(strcmp($allowedTypes[$x], $part[1]) == 0) {
                return 1;
            }
        }
    }
    return 0; //wrong mimetype
}  

//connect to DB
function connectToDB() {
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
        return $db;
    }
}

function checkExtension($fileName) {
    $allowedTypes = array("mp4", "avi");
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    for($x = 0; $x < count($allowedTypes); $x++) {
        if(strcmp($allowedTypes[$x], $extension) == 0) {
            echo "correct type 1<br/>";
            return 1;
        }
    }
    echo "not correct type 0<br/>";
    return 0;
}

//THE QUERY IS NOT RIGHT! JUST A PLACEHOLDER!!!
function checkAlreadyExist($fileName) {
    $db = connectToDB();
    $query = "SELECT username FROM video WHERE username='$fileName'";
    $result = pg_query($db, $query);
    while($row = pg_fetch_row($result)) {
        if(strcmp($row[0], $fileName) !== 0) {
            echo "$row[0]" . "$fileName <br/>";
            return 1;
        }
    }
    pg_close($db);
    return 0;
}

//return to upload.html page
//header("location: http://localhost/upload.html");
?>
