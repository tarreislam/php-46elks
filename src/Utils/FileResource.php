<?php


namespace Tarre\Php46Elks\Utils;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Exceptions\FileAlreadyExistsException;

/**
 * @property string fileUrl
 * @property BaseClient baseClient
 */
class FileResource
{
    protected $fileUrl;
    protected $baseClient;

    public function __construct(string $fileUrl, BaseClient $baseClient)
    {
        $this->fileUrl = $fileUrl;
        $this->baseClient = $baseClient;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getFileContent();
    }


    /**
     * This implementation might be stupid, but i dont understand what else they are trying to do xD
     *
     * Save the file resource on disk
     *
     * @param string $absolutePath
     * @param string $name
     * @param bool $replace
     * @return bool
     * @throws FileAlreadyExistsException
     */
    public function saveToDisk(string $absolutePath, string $name, $replace = false): bool
    {
        // create new name
        $path = $absolutePath . DIRECTORY_SEPARATOR . $name;

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
     * @return string|bool
     */
    protected function getFileContent()
    {
        // prepare auth
        $auth = base64_encode(sprintf('%s:%s', $this->baseClient->getAuthUsername(), $this->baseClient->getAuthPassword()));

        // prepare context
        $context = [
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Basic $auth\r\nContent-type: application/octet-stream\r\n\r\n"
            ]
        ];

        // create context
        $context = stream_context_create($context);

        // get file content
        return file_get_contents($this->fileUrl, false, $context);
    }

}
