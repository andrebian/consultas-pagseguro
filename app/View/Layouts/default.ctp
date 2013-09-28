<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Consultas de status do PagSeguro
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('style');
                
                echo $this->Html->script('plugins/jquery-1.7.min');
                echo $this->Html->script('plugins/jquery-ui-1.8.16.custom.min');
                echo $this->Html->script('plugins/colorpicker');
                echo $this->Html->script('custom/general');
                echo $this->Html->script('custom/elements');
                echo $this->Html->script('custom/tables');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-43949823-1', 'andrebian.com');
        ga('send', 'pageview');
    </script>
</head>
<body class="loggedin">

	<!-- START OF HEADER -->
	<div class="header radius3">
    	<div class="headerinner">
            <h1 style="color:white;">Consultas PagSeguro</h1>
        </div><!--headerinner-->
	</div><!--header-->
    <!-- END OF HEADER -->
        
    <!-- START OF MAIN CONTENT -->
    <div class="mainwrapper">
     	<div class="mainwrapperinner">
         
        <div class="maincontent">
                <div class="anuncio">
                    <script type="text/javascript">
                        <!--
                        google_ad_client = "ca-pub-2136784768929334";
                        /* Topo Consultas Pagseguro */
                        google_ad_slot = "4903579009";
                        google_ad_width = 970;
                        google_ad_height = 90;
                        //-->
                    </script>
                    <script type="text/javascript" src="//pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                </div>
                <br /><br /><br /><br /><br /><br /><br />
        	<div class="maincontentinner">
            	
                <?php echo $this->Session->flash(); ?>

		<?php echo $this->fetch('content'); ?>
                
                </div><!--maincontentinner-->
            
            <div class="footer">
            	<p>Desenvolvido por : <a href="http://andrebian.com">Andre Cardoso</a></p>
            </div><!--footer-->
            
        </div><!--maincontent-->
        
                
     	</div><!--mainwrapperinner-->
    </div><!--mainwrapper-->
	<!-- END OF MAIN CONTENT -->
    

</body>
</html>