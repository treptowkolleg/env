<?php

namespace TreptowKolleg;

class FileSystem
{
    private string $path;

    public function __construct(Path $path = Path::LOCAL_DIR, string $subDir = null, string $customEnv = null) {

        $this->path = match ($path) {
            Path::LOCAL_DIR => $this->getRootPath(),
            Path::USER_DIR => $this->getUserPath(),
            Path::PUBLIC_DIR => $this->getPublicPath(),
            Path::CUSTOM_DIR => $this->getCustomPath($customEnv),
        };
        if($subDir) {
            $this->initSubDir($subDir);
        }
    }

    private function getPublicPath(): string
    {
        return $this->getCustomPath("PUBLIC");
    }

    private function getRootPath(): string
    {
        if(!defined('ROOT')) {
            define('ROOT', realpath(dirname($_SERVER['PHP_SELF'])) . DIRECTORY_SEPARATOR);
        }
        return ROOT;
    }

    private function getUserPath(): string
    {
        $env = getenv();
        if (is_array($env)) {
            # Windows
            if(key_exists("HOMEDRIVE", $env) and array_key_exists("HOMEPATH", $env)) {
                return $root = $env["HOMEDRIVE"] . $env["HOMEPATH"] . DIRECTORY_SEPARATOR;
            }
            # Linux, MacOS
            if(key_exists("HOME", $env)) {
                return $root = $env["HOME"] . DIRECTORY_SEPARATOR;
            }
        }
        # Fallback
        return $this->getRootPath();
    }

    private function getCustomPath(string $envKey = null): string
    {
        if ( $envKey != null and array_key_exists($envKey, getenv()) ) {
            return getenv($envKey) . DIRECTORY_SEPARATOR;
        }  else {
            # Fallback
            return $this->getRootPath();
        }
    }

    private function initSubDir(string $subDir): void
    {
        $path = trim($subDir,"/");
        if(!is_dir($dirPath = $this->path . $path)){
            if(!mkdir($dirPath, recursive: true)) {
                exit("Fehler beim Erstellen des Ordners $dirPath");
            }
        }
        $this->path = str_replace("/", DIRECTORY_SEPARATOR, $dirPath.DIRECTORY_SEPARATOR);
    }

    public function readAsStream(string $file): void
    {
        $handle = fopen($path = $this->getFilePath($file), "rb");
        if(!$handle) exit("Kann Datei nicht öffnen: ".$path);

        $i = 1;
        while(!feof($handle)) {
            echo $i . ": " . fgets($handle);
            $i++;
        }
        fclose($handle);
    }

    public function writeAsStream(string $file, string $content = ""): bool|int
    {
        $handle = fopen($path = $this->getFilePath($file), "wb");
        if(!$handle) exit("Kann Datei nicht schreiben: ".$path);

        $bytesWritten = fputs($handle, $content);
        fclose($handle);
        return $bytesWritten;
    }

    public function getFileContentAsArray(string $file, bool $skipEmptyLines = true): bool|array
    {
        $filePath = $this->getFilePath($file);

        if(!file_exists($filePath)) return false;
        if($skipEmptyLines) return file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        else return file($filePath, FILE_IGNORE_NEW_LINES);
    }

    public function getFileContentAsString(string $file, bool $skipEmptyLines = true): bool|string
    {
        $filePath = $this->getFilePath($file);

        if(!file_exists($filePath)) return false;
        if($skipEmptyLines) return file_get_contents($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        else return file_get_contents($filePath, FILE_IGNORE_NEW_LINES);
    }

    public function putFileContentFromArray(string $file, array $content): bool|int
    {
        return file_put_contents($this->getFilePath($file), implode("\n", $content) );
    }

    public function putFileContentFromString(string $file, string $content): bool|int
    {
        return file_put_contents($this->getFilePath($file), $content);
    }

    public function getFilePath(string $file): string
    {
        return $this->path.$file;
    }

    public function getPath(): string
    {
        return $this->path;
    }

}