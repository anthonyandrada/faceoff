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
        //Get metadata of file
        $metadata = getMetadata($finalDest);
        //Store metadata and vidname into db
        storeToDB($fileName, $metadata);
        //extract the frames to a folder
        extractFrames($finalDest);
    } else {
        echo "Upload failed!!";
    }
} else {
    echo "File name already exists in DB.";
}

//------------------------ FUNCTIONS BELOW ------------------------

function extractFrames($path) {
    $folder = $_SERVER['DOCUMENT_ROOT'] . "/images/" . pathinfo($path, PATHINFO_FILENAME);
//    echo "folder: ".$folder."<br>";
    shell_exec("mkdir -p '$folder'");
    shell_exec("ffmpeg -v quiet -i '$path' '$folder'/%d.png -hide_banner");
    shell_exec("chmod 444 '$folder'/"); //not really working...
    return 0;
}

function storeToDB($fileName, $metadata) {
    $db = connectToDB();
    //frames, width, height, fps, name
    $query = "INSERT INTO video VALUES($metadata[2], $metadata[1], $metadata[0], 30, '$fileName')";
    $result = pg_query($db, $query);
    pg_close($db);
    return 0;
}


function getMetadata($path) {
    $string = shell_exec("ffprobe -print_format json -show_streams '$path'");
    //var_dump(json_decode($string));
    $jason = json_decode($string);
    $height = $jason->streams[0]->height;
    $width = $jason->streams[0]->width;
    $totalFrames = intval($jason->streams[0]->nb_frames);
    $duration = floatval($jason->streams[1]->duration);
    //$avgFPS = $jason->streams[0]->r_frame_rate;
    $avgFPS = floatval(number_format($totalFrames / $duration, 4));
    $result = array($height, $width, $totalFrames, $duration, $avgFPS);
    return $result;
}

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
    $host        = "host=localhost";
    $port        = "port=5432";
    $dbname      = "dbname=cs160";
    $credentials = "user=postgres password=student";
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
