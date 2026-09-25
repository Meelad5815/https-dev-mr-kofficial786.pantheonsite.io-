<?php
/**
 * Lightweight static QA for the MRK Digital classic theme.
 *
 * This intentionally does not require a live WordPress installation.
 */

declare(strict_types=1);

$theme = dirname(__DIR__) . '/wp-content/themes/mrk-digital';
$required = array(
	'functions.php',
	'header.php',
	'footer.php',
	'front-page.php',
	'index.php',
	'page.php',
	'single.php',
	'404.php',
	'search.php',
	'archive-mrk_service.php',
	'archive-mrk_project.php',
	'single-mrk_service.php',
	'single-mrk_project.php',
	'style.css',
);

$errors = array();

foreach ($required as $file) {
	if (! is_file($theme . '/' . $file)) {
		$errors[] = "Missing required theme file: {$file}";
	}
}

$phpFiles = array();
$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator($theme, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
	if ($file->isFile() && $file->getExtension() === 'php') {
		$phpFiles[] = $file->getPathname();
	}
}

$functions = array();
$dangerous = array(
	'eval',
	'shell_exec',
	'system',
	'passthru',
	'proc_open',
	'popen',
);

foreach ($phpFiles as $file) {
	$source = file_get_contents($file);
	if ($source === false) {
		$errors[] = "Unable to read: {$file}";
		continue;
	}

	$tokens = token_get_all($source);
	$count = count($tokens);

	for ($i = 0; $i < $count; $i++) {
		$token = $tokens[$i];
		if (! is_array($token) || $token[0] !== T_FUNCTION) {
			continue;
		}

		$j = $i + 1;
		while ($j < $count && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) {
			$j++;
		}
		if ($j < $count && $tokens[$j] === '&') {
			$j++;
		}
		while ($j < $count && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) {
			$j++;
		}
		if ($j < $count && is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
			$name = strtolower($tokens[$j][1]);
			$functions[$name][] = $file;
		}
	}

	foreach ($dangerous as $name) {
		if (preg_match('/\\b' . preg_quote($name, '/') . '\\s*\\(/i', $source)) {
			$errors[] = "Disallowed execution function found in {$file}: {$name}()";
		}
	}
}

foreach ($functions as $name => $files) {
	if (count($files) > 1) {
		$errors[] = 'Duplicate function declaration "' . $name . '" in: ' . implode(', ', $files);
	}
}

if ($errors) {
	fwrite(STDERR, "MRK theme QA failed:\n");
	foreach ($errors as $error) {
		fwrite(STDERR, " - {$error}\n");
	}
	exit(1);
}

echo "MRK theme static QA passed: " . count($phpFiles) . " PHP files scanned.\n";
