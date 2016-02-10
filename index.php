<?php

// saving filename
$saveFilename = 'save';

// get real path for our folder
$rootPath = realpath('./files');

// init archive object
$zip = new ZipArchive();
$zip->open($tempfile = tempnam(sys_get_temp_dir(),'zip_'), ZIPARCHIVE::CREATE);

/**
 * Create recursive directory iterator
 *
 * @var SplFileInfo[] $files
 */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath)
);

foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        // get real and relative path for current file
        $filePath     = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// create zip archive
$zip->close();

header('Content-Disposition: attachment; filename=' . $saveFilename . '.zip');
header('Content-Type: application/zip');
readfile($tempfile);
unlink($tempfile);
