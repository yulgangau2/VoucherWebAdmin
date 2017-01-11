<?php
include ("connectdb.php");
?>



<!DOCTYPE html>
<htmls>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnglishVoucher_31AUG</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="assets/css/user.css">
    <link rel="stylesheet" href="assets/css/AllVoucher.css">
</head>

<body>
<?php
    $subject=$_GET['subject'];
    $numrow=$_GET['num'];
    
?>

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header"><a class="navbar-brand navbar-link" href="http://www.asx-91.com/"><strong>CAMT ENGLISH VOUCHER</strong></a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav navbar-right">
                    <li role="presentation"><a href="#homeworksec"><strong>OVERVIEW </strong></a></li>
                    <li role="presentation"><a href="#linksec"><strong>ALL VOUCHER</strong></a></li>
                    <li role="presentation"><a href="#countdownsec"><strong>STUDENT INFO</strong></a></li>
                    <li role="presentation"><a href="#schedulesec" class="page-scroll"><strong>LOG OUT</strong></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-xs-10 col-xs-offset-1 firstcard">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1 class="hidden-xs title">ENGLISH @ CAMT'59</h1>
                    <h3 class="hidden-sm hidden-md hidden-lg title bold">ENGLISH @ CAMT'59</h3></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-xs-10 col-xs-offset-1 card">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><img src="https://udemy-images.udemy.com/course/750x422/62606_8221_7.jpg" class="img-responsive thumbnail"></div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                    <?php
                

                $query=mysqli_query($voucher,"SELECT * From student_has_voucher where voucher_id = '$subject'");

                        $row = mysqli_fetch_assoc($query);


                    ?>


                    <h2 class="text-center bold">ENGLISH @ CAMT'59</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr></tr>
                            </thead>
                            <tbody>

        
                                <tr>
                                    <td class="left">Status </td>
                                    <?php
 if($row['status']== 'completed'){
                            echo '<td> <span class="badge complete list">
                                '.$row['status'].'
                              
                            </span></td>';
                            }else if($row['status']== 'pending'){
                                 echo '<td><a href="#"><span class="badge public list">
                                '.$row['status'].'
                            </span></a></td>';
                            }else{
                                 echo '<td> <span class="badge expired list">
                                '.$row['status'].'
                              
                            </span></td>';
                            }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="left">Enrollment </td>
                                    <td class="right"><?php echo $numrow ?></td>
                                </tr>
                                <tr>
                                    <td class="left">Release Date</td>
                                    <td class="right">14 July 2016</td>
                                </tr>
                                <tr>
                                    <td class="left">Expired Date</td>
                                    <td class="right">14 July 2017</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1 card">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="id-col">Student ID</th>
                            <th class="hidden-xs">Firstname </th>
                            <th class="hidden-xs">Lastname </th>
                            <th class="status-col">Status </th>
                            <th class="profile-col">Profile</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query2 = mysqli_query($voucher,"SELECT * From student_has_voucher where voucher_id = '$subject'");
                        while ($row = mysqli_fetch_assoc($query2)) {
                           $user=$row['student_id'];
    $q_student=mysqli_query($camt,"SELECT * FROM students where id = '$user'");
        $q_stud_row=mysqli_fetch_assoc($q_student);


           //print_r ($q_student);
                          

                            
                        ?>

                        <tr>
                            <td><?php echo $row['student_id'];?></td>
                            <td class="hidden-xs"><?php echo $q_stud_row['fname_th'];?></td>
                            <td class="hidden-xs"><?php echo $q_stud_row['sname_th'];?></td>
                            <?php
 if($row['status']== 'completed'){
                            echo '<td> <span class="badge complete list">
                                '.$row['status'].'
                              
                            </span></td>';
                            }else if($row['status']== 'pending'){
                                 echo '<td><a href="#"><span class="badge public list">
                                '.$row['status'].'
                            </span></a></td>';
                            }else{
                                 echo '<td> <span class="badge expired list">
                                '.$row['status'].'
                              
                            </span></td>';
                            }
                                    ?>

                            <?php 
                            echo'<td><a href="Profile.php?id='.$user.'" class="profile"><span class="badge profile">Profile </span></a></td>
                        </tr>'
                        ?>  
                <?php
                }
                ?>
                        
                    </tbody>
                </table>
            </div>
            <hr>
            <nav class="text-center">
                <ul class="pagination">
                    <li><a aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                    <li class="active"><a>1</a></li>
                    <li><a>2</a></li>
                    <li><a>3</a></li>
                    <li><a>4</a></li>
                    <li><a>5</a></li>
                    <li><a aria-label="Next"><span aria-hidden="true">»</span></a></li>
                </ul>
            </nav>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Countdown.js"></script>
</body>

</html>