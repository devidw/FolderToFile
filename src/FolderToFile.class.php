<?php

/**
 *
 */
class FolderToFile
{
    public $files;
    public $adoc;

    public function __construct(
        public string $inputDir,
        public string $outputFile,
        public array $allowedExtensions
    ) {
        $this->getFileList();
        $this->merge();
        $this->generate();
    }

    /**
     * @param string $path
     * @return string
     */
    public function getExtension(string $path)
    {
        $parts = pathinfo($path);
        $extension = $parts['extension'];
        return $extension;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function hasAllowedExtension(string $path): bool
    {
        $extension = $this->getExtension($path);
        return in_array($extension, $this->allowedExtensions);
    }

    /**
     * @see https://stackoverflow.com/a/24784020/13765033
     */
    public function getFileList()
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->inputDir));
        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }
            if ($this->hasAllowedExtension($file->getFilename())) {
                $this->files[] = $file->getPathname();
            }
        }
    }

    /**
     *
     */
    public function merge()
    {
        $this->adoc = '';
        foreach ($this->files as $file) {
            $basename = str_replace($this->inputDir, '', $file);
            $extension = $this->getExtension($file);
            $contents = file_get_contents($file);
            $this->adoc .= <<<ADOC

            .$basename
            [source, $extension]
            ----
            $contents
            ----

            <<<

            ADOC;
        }
    }

    /**
     *
     */
    public function generate()
    {
        $written = file_put_contents($this->outputFile, $this->adoc);
        if ($written) {
            $cmd = "asciidoctor-pdf $this->outputFile";
            return shell_exec($cmd);
        } else {
            return false;
        }
    }
}
