<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Admin <?= isset($this->pageTitle) ? $this->pageTitle : '' ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?=base_url()?>assets/css/material-dashboard.css" rel="stylesheet" />
    <link href="<?=base_url()?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/materialise.font.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url()?>assets/css/custom.css" rel="stylesheet" />
    <link href="<?=base_url()?>assets/css/common.css" rel="stylesheet" />
</head>

<body>
    <div class="wrapper">
        <div class="sidebar" data-active-color="rose" data-background-color="black">
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="<?=base_url()?>assets/img/avatar.png" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="dashboard.html#collapseExample" class="collapsed">
                            Tania Andrew
                            <b class="caret"></b>
                        </a>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="dashboard.html#">My Profile</a>
                                </li>
                                <li>
                                    <a href="dashboard.html#">Edit Profile</a>
                                </li>
                                <li>
                                    <a href="dashboard.html#">Settings</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('backend/inc/sidebar.php'); ?>
            </div>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-transparent navbar-absolute">
                <div class="container-fluid">
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                            <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                            <i class="material-icons visible-on-sidebar-mini">view_list</i>
                        </button>
                    </div>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?=current_url()?>"> <?=isset($this->navTitle) ? $this->navTitle : 'Default Title'?> </a>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div class="container-fluid">
                    <?php 
                        if (isset($viewFile)){
                            $this->load->view($viewFile);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    var BASE_URL = '<?=base_url()?>';
    var BACKEND_BASE_URL = BASE_URL + 'backend';
</script>

<script src="<?=base_url()?>assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/material.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/jquery.validate.min.js" type="text/javascript"></script>

<script>
    $("#alertMessage").fadeOut(6000, function(){
        this.remove();
    });

    (function(){
        isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;

        if (isWindows && !$('body').hasClass('sidebar-mini')){
            // if we are on windows OS we activate the perfectScrollbar function
            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

            $('html').addClass('perfect-scrollbar-on');
        } else {
            $('html').addClass('perfect-scrollbar-off');
        }
    })();
</script>

<?php
if (isset($footerJs) && !empty($footerJs))
{
    foreach($footerJs as $javascript)
    {
        echo sprintf("<script src='%s%s'></script>", base_url(), $javascript);
    }
}
?>
<script src="<?=base_url()?>assets/js/Constant.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/Lodash.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/IEWebsite.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/IEWebsiteAdmin.js" type="text/javascript"></script>
</html>