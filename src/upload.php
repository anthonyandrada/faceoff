<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Face Off - CS160 Project Website</title>
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
    </head>

    <body>
        <?php
        $message = "";
        if(isset($_POST['submit'])) {
            $fileName = basename($_FILES['filename']['name']);
            //check extension that was provided before actually uploading the file
            $correctExtension = checkExtension($fileName);
            if($correctExtension) {
                //check to see if filename already exists in the database
                $alreadyExist = checkAlreadyExist($fileName);
                //continue is filename is not in database
                if($alreadyExist == 0) {
                    define ('SITE_ROOT', realpath(dirname(__FILE__)));
                    echo "siteRoot: " . SITE_ROOT . "<P>";
                    $finalDest = SITE_ROOT . "/video/" . basename($_FILES['filename']['name']);
                    $tempName = $_FILES['filename']['tmp_name'];
                    echo "tempName: " . $tempName ."<p>";
                    //actually check if the file is a video
                    $mimetype = mime_content_type($tempName);
                    $correctMimeType = checkMimeType($mimetype);
                    //Save file to server if correct mime type
                    if($correctMimeType == 1) {
                        echo "finalDest: " . $finalDest . "<p>";
                        $result = move_uploaded_file($tempName, $finalDest);
                    }
                    if($result) {
                        $message = "Upload successful!!";
                        //Get metadata of file
                        echo "metadata param: " . $finalDest . "<P>"; //chek
                        $metadata = getMetadata($finalDest);
                        //Store metadata and vidname into db
                        storeToDB($fileName, $metadata);
                        //extract the frames to a folder
                        extractFrames($finalDest);
                    } else {
                        $message = "Upload failed!!";
                    }
                } else {
                    $message = "File name already used in DB for this user.";
                }
            }
        }

        //------------------------ FUNCTIONS BELOW ------------------------

        function extractFrames($path) {
            echo "=================<p>";
            $folder = SITE_ROOT . "/extractedFrames/" . pathinfo($path, PATHINFO_FILENAME);
            echo "folder: ".$folder."<br>";
            shell_exec("mkdir -p '$folder'");
            shell_exec("ffmpeg -v quiet -i '$path' '$folder'/%d.png -hide_banner");
            shell_exec("chmod 444 '$folder'/"); //not really working...
            return 0;
        }

        function storeToDB($fileName, $metadata) {
            $db = connectToDB();
            //uuid, filename, frames, width, height, fps, username
            $query = "INSERT INTO video VALUES(uuid_generate_v4(), '$fileName', $metadata[2], $metadata[1], $metadata[0], $metadata[3], 'fakeusername')";
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
            $avgFPS = $jason->streams[0]->r_frame_rate;
            //$message = "<br>$totalFrames<br>$avgFPS<br>$duration<br>";
            //$avgFPS = floatval(number_format($totalFrames / $duration, 4));
            $result = array($height, $width, $totalFrames, $avgFPS, $duration);
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
            $host        = "host=127.0.0.1";
            $port        = "port=5432";
            $dbname      = "dbname=cs160";
            $credentials = "user=postgres password=asdf";
            $db = pg_connect("$host $port $dbname $credentials");
            //delete if statement after testing
            if($db){
                return $db;
            } else {
                $message = "Connection to database failed!<br/>";
            }
        }

        function checkExtension($fileName) {
            $allowedTypes = array("mp4", "avi");
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            for($x = 0; $x < count($allowedTypes); $x++) {
                if(strcmp($allowedTypes[$x], $extension) == 0) {
                    //echo "correct type<br/>";
                    return 1;
                }
            }
            $message = "File type not accepted.";
            return 0;
        }

        function checkAlreadyExist($fileName) {
            $db = connectToDB();
            $query = "SELECT filename FROM video WHERE filename='$fileName'";
            $result = pg_query($db, $query);
            while($row = pg_fetch_row($result)) {
                if(strcmp($row[0], $fileName) !== 0) {
                    return 1;
                }
            }
            pg_close($db);
            return 0;
        }

        ?>


        <!-- Nav bar -->
        <nav class="navbar navbar-inverse">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html"><img src="images/logo.png"></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Action</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Action</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <!-- jumbotron -->
        <div class="jumbotron text-center">
            <div class="container">
                <br>
                <h1>FACE OFF - Members Area :)</h1>
                <p>Upload videos here!</p>
                <br>
            </div>
        </div>

        <!-- Upload Panel -->
        <form class="container" id="form" method="post" action="#" enctype="multipart/form-data">
            <div class="row setup-content" id="step-1">
                <div class="col-xs-12">
                    <div class="col-md-12 text-center">
                        <label for="fileToUpload">Select a File to Upload</label><br />
                        <input type="file" name="filename"/>
                    </div>
                    <div id="message">

                        <?php
                        echo $message;
                        ?>

                    </div>
                    <input type="submit" onclick="uploadFile()" name="submit" value="submit"/>
                    <div id="progressNumber"></div>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="container">
            <hr>
            <table class="table table-striped">
                <tr class="warning">
                    <th>Thumbnail</th>
                    <th>File Name</th>
                    <th>Processed</th>
                </tr>
                <tr>
                    <td>Jill</td>
                    <td>Smith</td>
                    <td>50</td>
                </tr>
            </table>
        </div>

        <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
        <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>
