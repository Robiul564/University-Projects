<?php
// Vercel PHP router: routes incoming requests to existing PHP files
// Preserves application logic by including target PHP files unchanged.

// Get project root (parent of api/ directory)
$projectRoot = dirname(dirname(__FILE__));

$path = isset($_GET['path']) ? $_GET['path'] : '';
$path = urldecode($path);

// Security: normalize and prevent path traversal
$path = preg_replace('#/+#','/',$path);
$path = ltrim($path, '/');
$path = str_replace("..", "", $path);

if ($path === '' || $path === '/' || $path === 'api/router') {
    $candidates = [$projectRoot . '/index.php'];
} else {
    $candidates = [];
    $requested = $path;

    // Build full paths from project root
    $fullPath = $projectRoot . '/' . $requested;
    
    // direct file
    if (file_exists($fullPath) && is_file($fullPath)) {
        $candidates[] = $fullPath;
    }
    // try with .php
    if (file_exists($fullPath . '.php')) {
        $candidates[] = $fullPath . '.php';
    }
    // if directory, try index.php
    if (is_dir($fullPath) && file_exists($fullPath . '/index.php')) {
        $candidates[] = $fullPath . '/index.php';
    }
    // if request ends with slash
    if (substr($requested, -1) === '/' && file_exists($fullPath . 'index.php')) {
        $candidates[] = $fullPath . 'index.php';
    }
}

$target = null;
foreach ($candidates as $c) {
    if (file_exists($c) && is_file($c)) { $target = $c; break; }
}

if (!$target) {
    http_response_code(404);
    echo json_encode(['error' => '404 Not Found', 'path' => $path, 'candidates' => $candidates, 'projectRoot' => $projectRoot]);
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
$_SERVER['SCRIPT_NAME'] = '/' . ltrim(str_replace($projectRoot, '', $target), '/');
$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];

// Change working directory to the script's directory so relative includes work
chdir(dirname($target));

// Preserve original GET/POST/COOKIE data and include the target script
require $target;
