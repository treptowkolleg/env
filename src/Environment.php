<?php

namespace TreptowKolleg;

class Environment
{

    private FileSystem $fileSystem;

    private array $containers;

    public function __construct(Path $path = Path::LOCAL_DIR, string $subDir = null, string $customEnv = null)
    {
        $this->fileSystem = new FileSystem($path, $subDir, $customEnv);
        $file = null;
        if(file_exists(ROOT . ".env.local")) {
            $file = $this->fileSystem->getFileContentAsArray(".env.local");

        } elseif(file_exists(ROOT . ".env")) {
            $file = $this->fileSystem->getFileContentAsArray(".env");
        }
        foreach ($file as $line) {
            if ($line[0] != "#") {
                putenv($line);
            }
        }
    }

    public function getContainers(): array
    {
        return $this->containers;
    }

    public function getContainer(string $name): ?AttributeContainer
    {
        return $this->containers[$name] ?? null;
    }

    public function setContainers(array $containers): static
    {
        $this->containers = $containers;
        return $this;
    }

    public function addContainer(AttributeContainer $container): static
    {
        $this->containers[$container->getName()] = $container;
        return $this;
    }

    public function getFileSystem(): FileSystem
    {
        return $this->fileSystem;
    }

}