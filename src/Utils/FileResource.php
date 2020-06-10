<?php


namespace Tarre\Php46Elks\Utils;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Exceptions\FileAlreadyExistsException;

/**
 * @property string url
 */
class FileResource
{
    protected $fileName;

    public function __construct(string $url, BaseClient $baseClient)
    {
        // setup a temp file
        $this->fileName = tempnam(sys_get_temp_dir(), 'l46elks');

        // setup resource to sink file into
        $resource = fopen($this->fileName, 'w+');

        // perform request
        $baseClient->getGuzzleClient()->get($url, [
            'sink' => $resource
        ]);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getContent();
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getFileContent();
    }

    /**
     *
     * Save the file resource on disk
     *
     * @param string $absoluteDirectoryPath
     * @param string $fileName
     * @param bool $replace
     * @return bool
     * @throws FileAlreadyExistsException
     */
    public function saveToDisk(string $absoluteDirectoryPath, string $fileName, $replace = false): bool
    {
        // create new name
        $path = $absoluteDirectoryPath . DIRECTORY_SEPARATOR . $fileName;

        if (!$replace) {
            if (file_exists($path)) {
                throw new FileAlreadyExistsException(sprintf('File "%s" already exists, set $replace to true to ignore', $path));
            }
        }

        $content = $this->getFileContent();

        if (!$content) {
            return false;
        }

        // open new file for writing
        $fh = fopen($path, 'w+');
        // write file
        $bytesWritten = fwrite($fh, $content);
        // close stream
        fclose($fh);

        // return result
        return $bytesWritten !== false;
    }

    /**
     * This implementation might be stupid, but I dont understand the docs
     *
     * @return string|bool
     */
    protected function getFileContent()
    {
        // open file for read
        $resource = fopen($this->fileName, 'r');

        // return content
        return fread($resource, filesize($this->fileName));
    }

}
