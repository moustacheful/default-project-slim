# Slim starter project
Uses composer, redbeanphp, slim and has basic Facebook connect auth for simple social connected apps.

### Environment variables
This project uses phpdotenv. If `CLEARDB_DATABASE_URL` it will take precedence over any other DB related variables.

```
SLIM_MODE=development

DB_HOST=localhost
DB_NAME=yourdbname
DB_USER=root
DB_PASSWORD=

SITE_URL=http://yourdomain.com
TEMPLATES_PATH=../views
TEMPLATE_URL={$SITE_URL}

FB_APP_ID=YOUR_APP_ID
FB_APP_SECRET=YOUR_APP_SECRET
FB_LOGIN_CALLBACK={$SITE_URL}/api/login/cb

```

### Controllers

Put your controllers in  a class within `app/controller`. Methods should be static.

To get the app instance use `$app = Slim::getInstance()`.

### Routes

Routes are stored in `app/index.php` and call controller's static methods, eg.:

```php
	$app->get('/','PublicController::home');
	

	// This route requires middleware

	$app->post('/profile',$FBAuth(),'UserController::profile');

```

### Models

Redbean models are autoloaded with composer and are stored in `app/model`

### Middleware

Middleware and route middleware are stored in `app/middleware`. Global middleware should be classes extending `\Slim\Middleware`. Route middleware can be stored in route-middleware.php as functions.

Included middleware:

- JSON - if the request is XHR, the default response content-type is `text/json` 
- FBUser - Fills the view `user` and $app->user with the user information. Can be either `false` or a user redbean object.
- CORS - Enables CORS for allowed domains, e.g.:

```php
$app->add(new CORSMiddleware(array(
    'allowed_origins' => array('http://www.example1.com','http://www.example2.com')
)));
```