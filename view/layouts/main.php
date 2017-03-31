

<!DOCTYPE html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo isset($navTabs['title'])?$navTabs['title']:'后台操作系统'?></title>

    <link href="__PublicAdmin__/css/bootstrap.min.css" rel="stylesheet">
    <link href="__PublicAdmin__/css/site.css" rel="stylesheet">
    <script src="__PublicAdmin__/js/jquery.min.js"></script>
   <!-- <link href="__PublicAdmin__css/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
-->

    <script>
        window.UPDATE_URL = "{:Url('publics/img')}"
    </script>
</head>


<body style="min-width:790px;" >
<style>
    .alert{
        position: fixed !important;z-index: 1000;width: 98%;top: 2%;
    }

</style>
<div class="container-fluid">
    <div id="alert"></div>


    [__REPLACE__]
</div>


<script src="__PublicAdmin__/js/cmsinfo.js"></script>
<script src="__PublicDefault__/js/jquery-form.js" type="text/javascript"></script>
<!--弹出框-->
<!--<script src="__PublicAdmin__default/prettify/lhgdialog.min.js" type="text/javascript"></script>-->
<script src="__PublicAdmin__/js/bootstrap.min.js"></script>
</body>
</html>




