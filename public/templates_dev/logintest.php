<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script>
      var SITE_URL = "<?= SITE_URL ?>";
      var API_URL = "<?= SITE_URL.'api' ?>";
      var FB_APP_ID = "<?= $fb_app_id ?>";
    </script>
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : FB_APP_ID,
	      xfbml      : true,
	      version    : 'v2.2'
	    });

		FB.login(function(response) {
			$.post(SITE_URL+'api/login', {token:response.authResponse.accessToken}, function(data, textStatus, xhr) {
				console.log(data);
			});
		}, {scope: 'public_profile,email'});
	  };

	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>
</head>
<body>
	<a href="admin">admin</a>
</body>
</html>