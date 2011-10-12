<link rel='stylesheet' href="<?php echo get_bloginfo('url')?>/wp-content/plugins/thinkunremind/css/main.css" type='text/css' media='all' />
<script src="<?php echo get_bloginfo('url')?>/wp-content/plugins/thinkunremind/js/jquery.min.js"></script>
<script src="<?php echo get_bloginfo('url')?>/wp-content/plugins/thinkunremind/js/main.js"></script>
<div class="wrap">	
	<h2>Thinkun Remind Settings</h2>
	
<br/>
<br/>

<?php 

  $current = $_GET['function'] == '' ? "tab-menu" : $_GET['function'] ;
		 $$current = 'tab-menu';		
?>
<div class="thinkun-setting-tab">
	<ul>
		<?php if(isset($_GET['function'])) { ?>
		<li class="<?=isset($mailsetting)?$mailsetting:''?>"><a href="options-general.php?page=templatesetting&function=mailsetting">Mail settings</a></li>
		<?php } else { ?>
		<li class="tab-menu"><a href="options-general.php?page=templatesetting&function=mailsetting">Mail settings</a></li>
		<?php } ?>
		<li class="<?=isset($templateSetting)?$templateSetting:''?>"><a href="options-general.php?page=templatesetting&function=templateSetting">Email templates</a></li>
		<li class="<?=isset($customFieldSetting)?$customFieldSetting:''?>"><a href="options-general.php?page=templatesetting&function=customFieldSetting">Custom fields</a></li>		
	</ul>
</div>	
<div style="clear:both"></div>
	<?php if(isset($_GET['msg'])) {
		echo '<div class="info">'.$_GET['msg'].'</div>';
	}
	?>