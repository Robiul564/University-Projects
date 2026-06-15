<?php
// Vercel PHP router: routes incoming requests to existing PHP files
// Preserves application logic by including target PHP files unchanged.

$path = isset($_GET['path']) ? $_GET['path'] : '';
$path = urldecode($path);

// Security: normalize and prevent path traversal
$path = preg_replace('#/+#','/',$path);
$path = ltrim($path, '/');
$path = str_replace("..", "", $path);

if ($path === '' || $path === '/') {
    $candidates = ['index.php'];
} else {
    $candidates = [];
    $requested = $path;

    // direct file
    if (file_exists($requested) && is_file($requested)) {
        $candidates[] = $requested;
    }
    // try with .php
    if (file_exists($requested . '.php')) {
        $candidates[] = $requested . '.php';
    }
    // if directory, try index.php
    if (is_dir($requested) && file_exists($requested . '/index.php')) {
        $candidates[] = $requested . '/index.php';
    }
    // if request ends with slash
    if (substr($requested, -1) === '/' && file_exists($requested . 'index.php')) {
        $candidates[] = $requested . 'index.php';
    }
}

$target = null;
foreach ($candidates as $c) {
    if (file_exists($c) && is_file($c)) { $target = $c; break; }
}

if (!$target) {
    http_response_code(404);
    echo "404 Not Found";
    exit;
}

$ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
if ($ext !== 'php') {
    // static asset: stream file with proper mime
    $mime = function_exists('mime_content_type') ? mime_content_type($target) : 'application/octet-stream';
    header('Content-Type: ' . $mime);
    readfile($target);
    exit;
}

// Emulate requested script environment
$_SERVER['SCRIPT_FILENAME'] = realpath($target);
$_SERVER['SCRIPT_NAME'] = '/' . ltrim($target, '/');
$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];

// Change working directory to the script's directory so relative includes work
chdir(dirname($target));

// Preserve original GET/POST/COOKIE data and include the target script
require $target;
