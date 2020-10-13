<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Login</title>
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
    <div class="wrapper wrapper-full-page">
        <div class="full-page login-page" filter-color="black" data-image="../../assets/img/login.jpeg">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                            <form method="POST" action="<?=current_url()?>">
                                <div class="card card-login">
                                    <p class="category text-center">
                                        <br>
                                        <br>
                                    </p>
                                    <div class="ml-10 card-content">
                                        <div class="form-group label-floating">
                                            <label class="">Email address</label>
                                            <input type="text" name="userEmail" value="<?=set_value('userEmail')?>" autocomplete="off" class="form-control">
                                            <?=form_error('userEmail')?>
                                        </div>
                                        <div class="form-group label-floating">
                                            <label class="">Password</label>
                                            <input type="password" name="userPass" value="<?=set_value('userPass')?>" autocomplete="off" class="form-control">
                                            <?=form_error('userPass')?>
                                        </div>

                                        <?=!empty($flashMessage) ? '<p class="text-danger">'. $flashMessage . '</p>' : ''?>
                                    </div>
                                    <div class="footer text-center">
                                        <button type="submit" name="submit" value="Submit" class="btn btn-rose">Login</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    var BASE_URL = '<?=base_url()?>';
    var BACKEND_BASE_URL = BASE_URL + 'backend';
    var EXPORT_URL = "<?=isset($this->exportUrl) ? $this->exportUrl : ''?>";
</script>

<script src="<?=base_url()?>assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/material.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/sweetalert2.js" type="text/javascript"></script>
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
</html>