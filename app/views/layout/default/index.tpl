<!-- :: Shinigami Framework :: -->
<!-- :: v.2.0 :: -->
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang="es"> <!--<![endif]-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{$_params.configs.title}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Shinigami,Framework">   
        <meta name="author" content="Shinigami Framework">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

        <!--Stylesheet-->
        <link type="text/css" href="{$_public.addons}/bootstrap-3.1.0/css/bootstrap.min.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="{$_public.addons}/select2-3.4.5/select2.css" rel="stylesheet"/>
        <link type="text/css" href="{$_public.css}/default.css" rel="stylesheet"/>

        <!--Stylesheet-->
        {if isset($_params.resources.css) && count($_params.resources.css)}
            {foreach from=$_params.resources.css item=css}
                <link type="text/css" href="{$css|replace:$_root:($_site|cat:"/")}"  rel="stylesheet"/>
            {/foreach}
        {/if}
        <!--JavaScript-->
        <script type="text/javascript" async="async" >
            var site = '{$_site}';
            var base = '{$_base}';
        </script>
        <!-- JS Jquery 1.10.2 -->
        <script type="text/javascript" src="{$_public.addons}/jquery-1.10.2/jquery-1.10.2.min.js"></script>
        <!-- JS Bootstrap 3.0.3 -->
        <script type="text/javascript" src="{$_public.addons}/bootstrap-3.1.0/js/bootstrap.min.js"></script>
        <!-- JS Select 2 - 3.4.5 -->
        <script type="text/javascript" src="{$_public.addons}/select2-3.4.5/select2.js"></script> 

        <!-- Js Default app -->

        <script type="text/javascript" src="{$_public.js}/default.js"></script>

        {if isset($_params.resources.js) && count($_params.resources.js)}
            {foreach item=js from=$_params.resources.js}
                <script type="text/javascript" src="{$js|replace:$_root:($_site|cat:"/")}" charset="UTF-8" ></script>
            {/foreach}
        {/if}
    </head>
    <body>    

        <!-- Header -->
        <div class="navbar navbar-inverse" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Shinigami Framework</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Inicio</a></li>
                        <li><a href="#about">Acerca de</a></li>
                        <li><a href="#contact">Contacto</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Acción 1</a></li>
                                <li><a href="#">Acción 2</a></li>
                                <li><a href="#">Acción 3    </a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Sub-Menu</li>
                                <li><a href="#">Link 1</a></li>
                                <li><a href="#">Link 2</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="../navbar/">Link 1</a></li>
                        <li><a href="../navbar-static-top/">Link 2</a></li>
                        <li class="active"><a href="./">Link 3</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Header -->


        <!-- Content -->
        <div class="container">
            {include file=$_content}
        </div>
        <!-- /Content -->


        <!-- Footer -->
        <div class="navbar navbar-default navbar-fixed-bottom text-center text-muted">
            <h6>
                Shinigami Framework &copy; 2014 - Beta 0.9
            </h6>
        </div>
        <!-- /Footer -->
    </body>
</html>
