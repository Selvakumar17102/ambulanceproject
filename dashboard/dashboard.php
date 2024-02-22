<?php
    ini_set('display_errors','off');
    include('include/connection.php');
    $dashboard = 'active';
    $dashboardBoolean = 'true';

   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Dashboard | Instant Ambulance </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
   
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <style>
        .flex-space-between{
            display: flex;
            justify-content: space-between;
        }
        .inner-flex{
            display: flex;
            justify-content: flex-end;
            margin-bottom: 0px;
            color: #880E4F;
            font-weight: 600;
        }
    </style>

</head>
<body class="sidebar-noneoverflow">
   
    <!--  BEGIN NAVBAR  -->
    <?php include('include/header.php') ?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include('include/sidebar.php') ?>
        <!--  END SIDEBAR  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <?php include('include/notification.php') ?>

                <div class="row layout-top-spacing">
                    <div class="widget widget-table-four col-sm-12">
                        <center>
                            <div class="widget-content ">
                                <div class="flex-space-between ">
                                    <h2 class="">Welcome to Instant Ambulance Dashboard!!!!</h2>
                                </div>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
            <?php include('include/footer.php') ?>
        </div>
        <!--  BEGIN CONTENT AREA  -->
       
            <?php include('include/footer.php') ?>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/manual.js"></script>
    <script src="plugins/notification/snackbar/snackbar.min.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="plugins/apex/apexcharts.min.js"></script>
    <?php include('map.php') ?>
    <script src="assets/js/dashboard/dash_2.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

</body>
</html>