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
        // get API credentials
        $username = $this->baseClient->getAuthUsername();
        $password = $this->baseClient->getAuthPassword();

        // prepare auth
        $auth = base64_encode("$username:$password");

        // prepare request
        $request = [
            'http' => [
                'headers' => "Authorization: Basic $auth\r\n"
            ]
        ];

        // create context
        $context = stream_context_create($request);

        // get file content
        return file_get_contents($this->fileUrl, false, $context);
    }

}
