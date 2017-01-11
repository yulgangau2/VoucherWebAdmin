<?php


/*
* Copyright (c) 2008 http://www.webmotionuk.com / http://www.webmotionuk.co.uk
* "PHP & Jquery image upload & crop"
* Date: 2008-11-21
* Ver 1.2
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
* IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
* PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
* STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF 
* THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
*/
error_reporting (E_ALL ^ E_NOTICE);
session_start(); //Do not remove this
//only assign a new timestamp if the session variable is empty
if (!isset($_SESSION['random_key']) || strlen($_SESSION['random_key'])==0){
    $_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s')); //assign the timestamp to the session variable
    $_SESSION['user_file_ext']= "";
}
#########################################################################################################
# CONSTANTS                                                                                             #
# You can alter the options below                                                                       #
#########################################################################################################
$upload_dir = "upload_pic";                 // The directory for the images to be saved in
$upload_path = $upload_dir."/";             // The path to where the image will be saved
$large_image_prefix = "resize_";            // The prefix name to large image
$thumb_image_prefix = "thumbnail_";         // The prefix name to the thumb image
$large_image_name = $large_image_prefix.$_SESSION['random_key'];     // New name of the large image (append the timestamp to the filename)
$thumb_image_name = $thumb_image_prefix.$_SESSION['random_key'];     // New name of the thumbnail image (append the timestamp to the filename)
$max_file = "3";                            // Maximum file size in MB
$max_width = "500";                         // Max width allowed for the large image
$thumb_width = "100";                       // Width of thumbnail image
$thumb_height = "100";                      // Height of thumbnail image
// Only one of these image types should be allowed for upload
$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // do not change this
$image_ext = "";    // initialise variable, do not change this.
foreach ($allowed_image_ext as $mime_type => $ext) {
    $image_ext.= strtoupper($ext)." ";
}


##########################################################################################################
# IMAGE FUNCTIONS                                                                                        #
# You do not need to alter these functions                                                               #
##########################################################################################################
function resizeImage($image,$width,$height,$scale) {
    list($imagewidth, $imageheight, $imageType) = getimagesize($image);
    $imageType = image_type_to_mime_type($imageType);
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
    switch($imageType) {
        case "image/gif":
            $source=imagecreatefromgif($image); 
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            $source=imagecreatefromjpeg($image); 
            break;
        case "image/png":
        case "image/x-png":
            $source=imagecreatefrompng($image); 
            break;
    }
    imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
    
    switch($imageType) {
        case "image/gif":
            imagegif($newImage,$image); 
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            imagejpeg($newImage,$image,90); 
            break;
        case "image/png":
        case "image/x-png":
            imagepng($newImage,$image);  
            break;
    }
    
    chmod($image, 0777);
    return $image;
}
//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
    list($imagewidth, $imageheight, $imageType) = getimagesize($image);
    $imageType = image_type_to_mime_type($imageType);
    
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
    switch($imageType) {
        case "image/gif":
            $source=imagecreatefromgif($image); 
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            $source=imagecreatefromjpeg($image); 
            break;
        case "image/png":
        case "image/x-png":
            $source=imagecreatefrompng($image); 
            break;
    }
    imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
    switch($imageType) {
        case "image/gif":
            imagegif($newImage,$thumb_image_name); 
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            imagejpeg($newImage,$thumb_image_name,90); 
            break;
        case "image/png":
        case "image/x-png":
            imagepng($newImage,$thumb_image_name);  
            break;
    }
    chmod($thumb_image_name, 0777);
    return $thumb_image_name;
}
//You do not need to alter these functions
function getHeight($image) {
    $size = getimagesize($image);
    $height = $size[1];
    return $height;
}
//You do not need to alter these functions
function getWidth($image) {
    $size = getimagesize($image);
    $width = $size[0];
    return $width;
}

//Image Locations
$large_image_location = $upload_path.$large_image_name.$_SESSION['user_file_ext'];
$thumb_image_location = $upload_path.$thumb_image_name.$_SESSION['user_file_ext'];

//Create the upload directory with the right permissions if it doesn't exist
if(!is_dir($upload_dir)){
    mkdir($upload_dir, 0777);
    chmod($upload_dir, 0777);
}

//Check to see if any images with the same name already exist
if (file_exists($large_image_location)){
    if(file_exists($thumb_image_location)){
        $thumb_photo_exists = "<img src=\"".$upload_path.$thumb_image_name.$_SESSION['user_file_ext']."\" alt=\"Thumbnail Image\"/>";
    }else{
        $thumb_photo_exists = "";
    }
    $large_photo_exists = "<img src=\"".$upload_path.$large_image_name.$_SESSION['user_file_ext']."\" alt=\"Large Image\"/>";
} else {
    $large_photo_exists = "";
    $thumb_photo_exists = "";
}

if (isset($_POST["upload"])) { 
    //Get the file information
    $userfile_name = $_FILES['image']['name'];
    $userfile_tmp = $_FILES['image']['tmp_name'];
    $userfile_size = $_FILES['image']['size'];
    $userfile_type = $_FILES['image']['type'];
    $filename = basename($_FILES['image']['name']);
    $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
    
    //Only process if the file is a JPG, PNG or GIF and below the allowed limit
    if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
        
        foreach ($allowed_image_types as $mime_type => $ext) {
            //loop through the specified image types and if they match the extension then break out
            //everything is ok so go and check file size
            if($file_ext==$ext && $userfile_type==$mime_type){
                $error = "";
                break;
            }else{
                $error = "Only <strong>".$image_ext."</strong> images accepted for upload<br />";
            }
        }
        //check if the file size is above the allowed limit
        if ($userfile_size > ($max_file*1048576)) {
            $error.= "Images must be under ".$max_file."MB in size";
        }
        
    }else{
        $error= "Select an image for upload";
    }
    //Everything is ok, so we can upload the image.
    if (strlen($error)==0){
        
        if (isset($_FILES['image']['name'])){
            //this file could now has an unknown file extension (we hope it's one of the ones set above!)
            $large_image_location = $large_image_location.".".$file_ext;
            $thumb_image_location = $thumb_image_location.".".$file_ext;
            
            //put the file ext in the session so we know what file to look for once its uploaded
            $_SESSION['user_file_ext']=".".$file_ext;
            
            move_uploaded_file($userfile_tmp, $large_image_location);
            chmod($large_image_location, 0777);
            
            $width = getWidth($large_image_location);
            $height = getHeight($large_image_location);
            //Scale the image if it is greater than the width set above
            if ($width > $max_width){
                $scale = $max_width/$width;
                $uploaded = resizeImage($large_image_location,$width,$height,$scale);
            }else{
                $scale = 1;
                $uploaded = resizeImage($large_image_location,$width,$height,$scale);
            }
            //Delete the thumbnail file so the user can create a new one
            if (file_exists($thumb_image_location)) {
                unlink($thumb_image_location);
            }
        }
        //Refresh the page to show the new uploaded image
        header("location:".$_SERVER["PHP_SELF"]);
        exit();
    }
}

if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
    //Get the new coordinates to crop the image.
    $x1 = $_POST["x1"];
    $y1 = $_POST["y1"];
    $x2 = $_POST["x2"];
    $y2 = $_POST["y2"];
    $w = $_POST["w"];
    $h = $_POST["h"];
    //Scale the image to the thumb_width set above
    $scale = $thumb_width/$w;
    $cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
    //Reload the page again to view the thumbnail
    header("location:".$_SERVER["PHP_SELF"]);
    exit();
}


if ($_GET['a']=="delete" && strlen($_GET['t'])>0){
//get the file locations 
    $large_image_location = $upload_path.$large_image_prefix.$_GET['t'];
    $thumb_image_location = $upload_path.$thumb_image_prefix.$_GET['t'];
    if (file_exists($large_image_location)) {
        unlink($large_image_location);
    }
    if (file_exists($thumb_image_location)) {
        unlink($thumb_image_location);
    }
    header("location:".$_SERVER["PHP_SELF"]);
    exit(); 
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnglishVoucher_10Jan</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="assets/css/user.css">
    <link rel="stylesheet" href="assets/css/AllVoucher.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">


    <style>
        .expired {
  background-color: #f44336;
 margin-top: 20px;
  padding-top: 5px;
  padding-bottom: 5px;
  padding-left: 10px;
  padding-right: 10px;
}
    </style>
</head>

<body>


<?php
//Only display the javacript if an image has been uploaded
if(strlen($large_photo_exists)>0){
    $current_large_image_width = getWidth($large_image_location);
    $current_large_image_height = getHeight($large_image_location);?>
<script type="text/javascript">
function preview(img, selection) { 
    var scaleX = <?php echo $thumb_width;?> / selection.width; 
    var scaleY = <?php echo $thumb_height;?> / selection.height; 
    
    $('#thumbnail + div > img').css({ 
        width: Math.round(scaleX * <?php echo $current_large_image_width;?>) + 'px', 
        height: Math.round(scaleY * <?php echo $current_large_image_height;?>) + 'px',
        marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
        marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
    });
    $('#x1').val(selection.x1);
    $('#y1').val(selection.y1);
    $('#x2').val(selection.x2);
    $('#y2').val(selection.y2);
    $('#w').val(selection.width);
    $('#h').val(selection.height);
} 

$(document).ready(function () { 
    $('#save_thumb').click(function() {
        var x1 = $('#x1').val();
        var y1 = $('#y1').val();
        var x2 = $('#x2').val();
        var y2 = $('#y2').val();
        var w = $('#w').val();
        var h = $('#h').val();
        if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
            alert("You must make a selection first");
            return false;
        }else{
            return true;
        }
    });
}); 

$(window).load(function () { 
    $('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>', onSelectChange: preview }); 
});

</script>
<?php }?>


<?php
//Display error message if there are any
if(strlen($error)>0){
    echo "<ul><li><strong>Error!</strong></li><li>".$error."</li></ul>";
}
if(strlen($large_photo_exists)>0 && strlen($thumb_photo_exists)>0){
    echo $large_photo_exists."&nbsp;".$thumb_photo_exists;
    echo "<p><a href=\"".$_SERVER["PHP_SELF"]."?a=delete&t=".$_SESSION['random_key'].$_SESSION['user_file_ext']."\">Delete images</a></p>";
    echo "<p><a href=\"".$_SERVER["PHP_SELF"]."\">Upload another</a></p>";
    //Clear the time stamp session and user file extension
    $_SESSION['random_key']= "";
    $_SESSION['user_file_ext']= "";
}else{
        if(strlen($large_photo_exists)>0){?>
        <h2>Create Thumbnail</h2>
        <div align="center">
            <img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" />
            <div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
                <img src="<?php echo $upload_path.$large_image_name.$_SESSION['user_file_ext'];?>" style="position: relative;" alt="Thumbnail Preview" />
            </div>
            <br style="clear:both;"/>
            <form name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                <input type="hidden" name="x1" value="" id="x1" />
                <input type="hidden" name="y1" value="" id="y1" />
                <input type="hidden" name="x2" value="" id="x2" />
                <input type="hidden" name="y2" value="" id="y2" />
                <input type="hidden" name="w" value="" id="w" />
                <input type="hidden" name="h" value="" id="h" />
                <input type="submit" name="upload_thumbnail" value="Save Thumbnail" id="save_thumb" />
            </form>
        </div>
    <hr />
    <?php   } ?>






    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1 firstcard">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h1 class="title bold">ALL VOUCHER</h1></div>
                <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                    <!-- button -->
<div class="dropdown btn-group" role="group">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="margin-right:5px;">View <span class="caret"></span></button>
        <ul role="menu" class="dropdown-menu">
            <li>
                <a href="ListView.html">
                    <table>
                        <tr>
                            <td style="width:50px"><span class="glyphicon glyphicon-list-alt"></span></td>
                            <td>List</td>
                        </tr>
                    </table>
                </a>
            </li>
            <li>
                <a href="CardView.html">
                    <table>
                        <tr>
                            <td style="width:50px"><span class="glyphicon glyphicon-th-list"></span></td>
                            <td>Card</td>
                        </tr>
                    </table>
                </a>
            </li>
            <li class="hidden-xs">
                <a href="GridView.html">
                    <table>
                        <tr>
                            <td style="width:50px"><span class="glyphicon glyphicon-th-large"></span></td>
                            <td>Grid</td>
                        </tr>
                    </table>
                </a>
            </li>
        </ul>
    </div>
    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#createvoucher">Create Voucher</button>
<!-- /button -->

<!-- Modal -->
<div id="createvoucher" class="modal fade" role="dialog">
  <div class="modal-dialog text-left">

<!-- start -->
    <!-- Modal content-->
    <div class="modal-content">
    <form name="photo" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <div class="modal-header" style="background-color:#ff9900; color:white; border-radius : 5px 5px 0px 0px">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title" style="font-weight:bold; padding-left:15px; padding-right:30px">Create Voucher</h2>
        </div>
        <div class="modal-body" style="padding-left:30px; padding-right:30px">
            
            
            <div class="form-group">
                <h3>Voucher Name</h3>
                <input type="text" name="text-input" class="form-control" id="text-input" />
            </div>
            <div class="form-group">
                <h3>Description</h3>
                <input type="text" name="text-input" class="form-control" id="text-input" />
            </div>
            <div class="form-group">
                <h3>Expired Date</h3>
                <input type="date" />
            </div>
            <div class="form-group">
                <h3>Voucher Picture</h3>
                <span class="btn btn-default btn-file">
                   Photo <input type="file" name="image" size="30" />

                </span>
                 Photo <input type="file" name="image" size="30" />
                <input type="submit" name="upload" value="Upload" />
            </div>
            
            
        
        </div>
        <div class="modal-footer">
        <div class="col-md-3 col-md-offset-9 col-sm-4 col-sm-offset-8">
            <button type="submit" class="btn btn-success btn-block" data-dismiss="modal" name="upload" value="Upload">Submit</button>
            <input type="submit" name="upload" value="Upload" class="btn btn-success btn-block" data-dismiss="modal" />
        </div>
        </div>
        </form>
        <?php } ?>
    </div>
  </div>
</div>
<!--  -->
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header"><a class="navbar-brand navbar-link"><strong>CAMT ENGLISH VOUCHER</strong></a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav navbar-right">
                    <li role="presentation"><a><strong>OVERVIEW </strong></a></li>
                    <li role="presentation"><a><strong>ALL VOUCHER</strong></a></li>
                    <li role="presentation"><a><strong>STUDENT INFO</strong></a></li>
                    <li role="presentation"><a class="page-scroll"><strong>LOG OUT</strong></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-xs-10 col-xs-offset-1 card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="list-col-1">Picture </th>
                            <th class="list-col-2">Name </th>
                            <th class="hidden-sm list-col-3">Enrolled </th>
                            <th class="list-col-4">Status </th>
                            <th class="list-col-5">More details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> <img src="https://udemy-images.udemy.com/course/750x422/62606_8221_7.jpg" class="list-img"></td>
                            <td>
                                <p class="list-margin">ENGLISH @ CAMT'59 </p>
                            </td>
                            <td class="hidden-sm">
                                <p class="list-margin">100 </p>
                            </td>
                            <td> <span class="badge expired">Expire </span></td>
                            <td> <a class="btn btn-primary bold list-margin" role="button" href="Details.html">More Details</a></td>
                        </tr>
                        <tr>
                            <td> <img src="https://udemy-images.udemy.com/course/750x422/62606_8221_7.jpg" class="list-img"></td>
                            <td>
                                <p class="list-margin">ENGLISH @ CAMT'59&nbsp; </p>
                            </td>
                            <td class="hidden-sm">
                                <p class="list-margin">100 </p>
                            </td>
                            <td> <span class="badge public list">Public </span></td>
                            <td> <a class="btn btn-primary bold list-margin" role="button" href="Details.html">More Details</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Countdown.js"></script>
</body>

</html>