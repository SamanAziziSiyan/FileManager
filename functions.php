<?php
/*
 * @param string $file Filepath
 * @param int $digits Digits to display
 * @return string|bool Size (KB, MB, GB, TB) or boolean
 */
function getNiceFileSize($file, $digits = 2)
{
    if (is_file($file)) {
        $filePath = $file;
        if (!realpath($filePath)) {
            $filePath = $_SERVER["DOCUMENT_ROOT"] . $filePath;
        }
        $fileSize = filesize($filePath);
        $sizes = array("TB", "GB", "MB", "KB", "B");
        $total = count($sizes);
        while ($total-- && $fileSize > 1024) {
            $fileSize /= 1024;
        }
        
        return round($fileSize, $digits) . " " . $sizes[$total];
    }
    return false;
}

function rmDirectory($dir)
{
    foreach (glob($dir . '/*') as $file) {
        if (is_dir($file))
            rmDirectory($file);
        else
            unlink($file);
    }
    rmdir($dir);
}
