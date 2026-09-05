<?php

$root = dirname(__DIR__);
$directory = $root . DIRECTORY_SEPARATOR . 'pg';
$violations = [];
$sqlPattern = '/\b(?:SELECT\s+(?:\*|COUNT|DISTINCT|[A-Za-z_]+\.)|INSERT\s+INTO|DELETE\s+FROM|UPDATE\s+(?:tbl|vw)[A-Za-z_]*|(?:FROM|JOIN|WHERE)\s+(?:tbl|vw)[A-Za-z_]*)/i';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
foreach ($iterator as $file) {
    if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;

    $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
    foreach (token_get_all(file_get_contents($file->getPathname())) as $token) {
        if (!is_array($token)) continue;
        [$type, $text, $line] = $token;
        if (!in_array($type, [T_CONSTANT_ENCAPSED_STRING, T_ENCAPSED_AND_WHITESPACE], true)) continue;
        if (preg_match($sqlPattern, $text)) $violations[] = $relative . ':' . $line;
    }
}

if ($violations !== []) {
    fwrite(STDERR, "SQL directo encontrado en pg:\n- " . implode("\n- ", $violations) . "\n");
    exit(1);
}

echo "OK: no hay sentencias SQL directas en pg.\n";
