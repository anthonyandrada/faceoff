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
    </head>
    <body>
        <?php
        $message = "";
        //if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //    $username = $_POST["username"];
        //}
        //else {
            $username = "cecilexpham";
        //}
        define ('SITE_ROOT', realpath(dirname(__FILE__)));
        if(isset($_POST['submit'])) {
            $fileName = basename($_FILES['filename']['name']);
            //check extension that was provided before actually uploading the file
            $correctExtension = checkExtension($fileName);
            if($correctExtension) {
                //check to see if filename already exists in the database
                $alreadyExist = checkAlreadyExist($fileName, $username);
                //continue is filename is not in database
                if($alreadyExist == 0) {
                    $newfolder = SITE_ROOT . "/video/{$username}";
                    shell_exec("mkdir -p '$newfolder'");
                    shell_exec("chmod 755 '$newfolder'");
                    $finalDest = SITE_ROOT . "/video/{$username}/" . basename($_FILES['filename']['name']);
                    $tempName = $_FILES['filename']['tmp_name'];
                    //actually check if the file is a video
                    $mimetype = mime_content_type($tempName);
                    $correctMimeType = checkMimeType($mimetype);
                    //Save file to server if correct mime type
                    if($correctMimeType == 1) {
                        $result = move_uploaded_file($tempName, $finalDest);
                    }
                    if($result) {
                        //====== finish doing everything else ======
                        $message = "Upload successful!!";
                        //Get metadata of file
                        $metadata = getMetadata($finalDest);
                        //Store metadata and vidname into db
                        storeToDB($fileName, $metadata, $username);
                        //extract the frames to a folder
                        extractFrames($finalDest, $username);
                        //put points onto frames in DB
                        faceData($finalDest, $username, $fileName);
                        //put pupil data onto frames in DB
                        eyeLike($finalDest, $username, $fileName);
                        //process frames with Delaunay triangle data
                        processFrames($finalDest, $username); 
                        //merge and output final video
                        mergeFrames($metadata[3], $fileName, $finalDest, $username);
                    } else {
                        $message = "Upload failed!!";
                    }
                } else {
                    $message = "File name already used in DB for this user.";
                }
            }
        }

        //------------------------ FUNCTIONS BELOW ------------------------

        function extractFrames($path, $username) {
            $folder = SITE_ROOT . "/extractedFrames/{$username}/" . pathinfo($path, PATHINFO_FILENAME);
            shell_exec("mkdir -p '$folder'");
            shell_exec("ffmpeg -v quiet -i '$path' '$folder'/%04d.png -hide_banner");
            shell_exec("chmod 755 '$folder'/");
            //good to have 644 permission for files, sensitive files = 700 permission
            return 0;
        }

        function processFrames($path, $username) {
            //JUST COPYING IMAGES OVER TO ANOTHER FOLDER FOR NOW!!!
            $source = SITE_ROOT . "/extractedFrames/{$username}/" . pathinfo($path, PATHINFO_FILENAME);
            $dest = SITE_ROOT . "/processedFrames/{$username}/";
            shell_exec("mkdir -p '$dest'");
            shell_exec("cp -R '$source' '$dest'");
            //USE OPENFACE TO PUT POINTS ONTO THE IMAGES
            return 0;
        }

        function mergeFrames($avgFPS, $fileName, $path, $username) {
            $folder = SITE_ROOT . "/processedFrames/{$username}/" . pathinfo($path, PATHINFO_FILENAME);
            $output = SITE_ROOT . "/outputVideos/{$username}/";
            shell_exec("mkdir -p '$output'");
            $fps = explode("/", $avgFPS);
            $averageFPS = $fps[0]/$fps[1];
            shell_exec("ffmpeg -framerate $averageFPS -i '$folder/%04d.png' -c:v libx264 -pix_fmt yuv420p '$output/$fileName'");
            return 0;
        }

        function storeToDB($fileName, $metadata, $username) {
            $db = connectToDB();
            //frames, width, height, fps, username, filename
            $query = "INSERT INTO video (frames, width, height, fps, username, filename) VALUES($metadata[2], $metadata[1], $metadata[0], $metadata[3], '$username', '$fileName')";
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
            $host        = "host=localhost";
            $port        = "port=5432";
            $dbname      = "dbname=cs160";
            $credentials = "user=postgres password=student";
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
                    return 1;
                }
            }
            $message = "File type not accepted.";
            return 0;
        }

        function checkAlreadyExist($fileName, $username) {
            $db = connectToDB();
            $query = "SELECT COUNT(*) FROM video WHERE filename='$fileName' AND username='$username'";
            $result = pg_query($db, $query);
            while($row = pg_fetch_row($result)) {
                if($row[0] > 0) {
                    return 1;
                }
            }
            pg_close($db);
            return 0;
        }

        function faceData($path, $username, $fileName) {
            $folder = SITE_ROOT . "/extractedFrames/{$username}/" . pathinfo($path, PATHINFO_FILENAME);
            shell_exec("mkdir -p '$folder'/landmark");
            shell_exec("cd /var/www/html/FaceLandmarkImage_exe; ./FaceLandmarkImg -fdir '$folder' -ofdir '$folder'/landmark");
            shell_exec("chmod 755 '$folder'/");
            $db = connectToDB();
            $query = "SELECT * FROM video WHERE filename='$fileName' AND username='$username'";
            $result = pg_query($db, $query);
            $row = pg_fetch_row($result);
            $count = $row[1];
            $vID = $row[0];
            for ($f = 1; $f < $count+1; $f++)
            {
                $padF = sprintf("%04d", $f);
                $query = "INSERT INTO image (video_id, image_id, filename) VALUES ('$vID', '$f', '$fileName/$padF.png')";
                $result = pg_query($db, $query);
                $file = fopen("$folder/landmark/$padF.png_landmark.txt", "r");
                $data = explode(",", fgets($file));
                $x = 1;
                $y = 2;
                for ($p = 1; $p < 69; $p++)
                {
                    $query = "UPDATE image SET data_$p = '($data[$x],$data[$y])' WHERE video_id = '$vID' AND image_id = '$f'";
                    $result = pg_query($db, $query);
                    $x++;
                    $x++;
                    $y++;
                    $y++;
                 }
                fclose($file);
            }
            pg_close($db);
            return 0;
         }
        
        function eyeLike($path, $username, $fileName) {
            $folder = SITE_ROOT . "/extractedFrames/{$username}/" . pathinfo($path, PATHINFO_FILENAME);
            $db = connectToDB();
            $query = "SELECT * FROM video WHERE filename='$fileName' AND username='$username'";
            $result = pg_query($db, $query);
            $row = pg_fetch_row($result);
            $count = $row[1];
            $vID = $row[0];
            for ($f = 1; $f < $count+1; $f++)
            {
                $padF = sprintf("%04d", $f);
                $output = shell_exec("cd /var/www/html/eyeLike-master/build/bin; ./eyeLike '$folder'/'$padF'.png");
                $data = explode(",", $output);
                $x = $data[0];
                $y = $data[1];
                $query = "UPDATE image SET of_left = '($data[$x],$data[$y])' WHERE video_id = '$vID' AND image_id = '$f'";
                $result = pg_query($db, $query);
                $x = $data[2];
                $y = $data[3];
                $query = "UPDATE image SET of_right = '($data[$x],$data[$y])' WHERE video_id = '$vID' AND image_id = '$f'";
                $result = pg_query($db, $query);
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
                                <li role="separator" class="divider"></li>
                                <li><a href="http://faceoff.ddns.net">Sign Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <!-- jumbotron -->
        <div class="content">
            <div class="jumbotron text-center">
                <div class="container">
                    <br>
                    <h1>FACE OFF - Members Area</h1>
                    <p><?php echo "Hi {$username}!"; ?></p>
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
                       <!-- <div class="bar">
                            <span class="bar-fill">
                                <span class="bar-text">Upload Progress</span>
                            </span>
                        </div> -->
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="container">
                <hr>
                <table class="table table-striped">
                    <tr class="warning">
                        <th class='td-thumb'>Thumbnail</th>
                        <th>Filename</th>
                        <th>Frames Extracted</th>
                        <th>Face Data Processed</th>
                        <th>Pupil Data Processed</th>
                        <th>Delaunay Triangulation</th>
                    </tr>
                    <?php
                    $db = connectToDB();
                    $query = "SELECT * FROM video WHERE username='$username'";
                    $result = pg_query($db, $query);
                    //video_id | frames | width | height | fps | username | filename | fr_processed | fd_processed | pd_processed | dt_processed
                    while($row = pg_fetch_row($result)) {
                        $filename = explode(".", $row[6]);
                        $directory = "./extractedFrames/{$username}/{$filename[0]}";
                        $thumbnail = $directory . "/0001.png";
                        $vidFile = "/video/{$username}/" . $row[6];
                        $numFiles = trim(shell_exec("ls $directory | wc -l"));

                        //WRITE HTML
                        echo "<tr>";
                        echo "<td>
                        <a data-fancybox class='thumbnail'rel='lightbox' title='$row[6]' data-poster='$thumbnail' href='$vidFile'><img class='img-responsive' alt='Image...' src='$thumbnail' /></a>
                        </td>";
                        echo "<td>" . $row[6] . "</td>"; //filename
                        if($row[7] < $row[1])
                        {    //frames processed
                           $query = "UPDATE video SET fr_processed = $numFiles WHERE filename = '$row[6]' AND username = '$username'";
                           pg_query($db, $query);
                           echo "<td>" . number_format(($row[7]/ (float)($row[1])) * 100, 2) . "% Complete</td>";
                        }
                        else {
                           echo "<td>Complete</td>";
                        }

                        //face data processed
                        echo "<td>";
                        echo "0% Complete";
                        echo "</td>";

                        //pupil data processed
                        echo "<td>";
                        echo "0% Complete";
                        echo "</td>";

                        //delaunay triangulation
                        echo "<td>";
                        echo "0% Complete";
                        echo "</td>";

                        echo "</tr>";
                    }
                    pg_close($db);
                    ?>
                </table>
            </div>
            <div style="margin-top: 100px;"></div>
        </div>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/jquery.fancybox.min.js"></script>
        <link rel="stylesheet" href="css/jquery.fancybox.min.css" />
        <script src="js/bootstrap.min.js"></script>
        <link href="css/custom.css" rel="stylesheet">
        <script src="js/custom.js"></script>
        <!-- <script src="js/progress.js"></script> -->
    </body>
</html>
