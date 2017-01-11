<?php
include ("connectdb.php");
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
        .expired{
  background-color: #f44336;
  padding: 5px 10px;
}
    </style>
</head>

<body>

<?php
    $id = $_GET['id'];
?>
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
        <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 firstcard">
            <h1 class="title bold">Student's Profile</h1></div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 card">
            <div class="row">
                <?php
                $student = mysqli_query($camt,"SELECT * FROM students where id ='$id'");
                $row = mysqli_fetch_assoc($student);
                ?>

                <div class="col-lg-4 col-md-4 col-sm-4 margin-bottom-25"><img src="assets/img/Profile Pic.jpg" class="listview"></div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="row margin15">
                        <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
                            <p class="title">Name </p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <p class="name"><?php echo $row['fname_th'] ?></p>
                        </div>
                    </div>
                    <div class="row margin15">
                        <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
                            <p class="title">Student ID</p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <p class="name"><?php echo $id; ?></p>
                        </div>
                    </div>
                    <div class="row margin15">
                        <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
                            <p class="title">Major </p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <p class="name"><?php echo $row['major_en'];?></p>
                        </div>
                    </div>
                    <div class="row margin15">
                        <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
                            <p class="title">Advisor </p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <p class="name">Pattaraporn Khuwuthyakorn </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
                            <p class="title">Status </p>
                        </div>
                        
                        <?php
                        echo '<div class="col-lg-8 col-md-8 col-sm-8">';
                        if($row['status']=="STUDYING"){
                        echo '<span class="badge  public">'.$row['status'].'</span>';
                        }else{

                        echo '<span class="badge expired">'.$row['status'].'</span></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 card">
            <h2>History </h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="his-col-1">Picture </th>
                            <th class="his-col-2">Name </th>
                            <th class="his-col-3">Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> <img src="https://udemy-images.udemy.com/course/750x422/62606_8221_7.jpg" class="list-img"></td>
                            <td>
                                <p class="list-margin his-list">ENGLISH @ CAMT'59 </p>
                            </td>
                            <td> <span class="badge his-studying">Studying </span></td>
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