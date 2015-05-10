<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PROJECT NAME</title>

    <!-- build:css <?=TEMPLATE_URL?>/css/styles.css -->
		<link rel="stylesheet" href="<?=TEMPLATE_URL?>/css/styles.css" />
    <!-- /build -->

	<script>
		var API_BASE = '<?=SITE_URL?>';
	</script>

	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<script src="http://connect.facebook.net/es_ES/all.js"></script>

	<!-- Modules here -->
	<!-- build:js <?=TEMPLATE_URL?>/js/modules.min.js -->

	<!-- /build -->
	
	<!-- App here -->
	<!-- build:js <?=TEMPLATE_URL?>/js/app.min.js -->
		<script src="http://localhost:35729/livereload.js"></script>
		<script src="<?=TEMPLATE_URL?>/js/app.js"></script>
	<!-- /build -->
</head>
<body>
	Hello.
</body>
</html>