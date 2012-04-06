</head>
<body>

<div id="menu">
	<ul class="menu_tabbed">
		<li><a href="<?=site_url()?>" <?=$title == 'Index' ? 'class="selected"' : ''?>>Home</a></li>
		<li><a href="<?=site_url().$page?>register" <?=$title == 'Register' ? 'class="selected"' : ''?>>Register</a></li>
		<li><a href="<?=site_url().$page?>login" <?=$title == 'Login' ? 'class="selected"' : ''?>>Login</a></li>
		<li><a href="<?=site_url().$page?>about" <?=$title == 'About Me' ? 'class="selected"' : ''?>>About Me</a></li>
	</ul>
</div>
<div id="container">