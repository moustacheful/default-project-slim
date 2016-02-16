<?php
class CommonMiddleware {
	public static function stripTrailingSlash($req, $res, $next) {
	    $uri = $req->getUri();
	    $path = $uri->getPath();
	    if ($path != '/' && substr($path, -1) == '/') {
	        // permanently redirect paths with a trailing slash
	        // to their non-trailing counterpart
	        $uri = $uri->withPath(substr($path, 0, -1));
	        return $res->withRedirect((string)$uri,301);
	    }

	    return $next($req, $res);
	}
}