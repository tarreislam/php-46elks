<?php


namespace Clients\Image;


use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\Image\Resources\Image;
use Tarre\Php46Elks\Utils\Paginator;

class ImageClientTest extends TestCase
{
    public function testGetAll()
    {
        $jsonToMock = <<<'EOT'
            {
              "data": [
                {
                  "created": "2016-11-03T12:44:56.190000",
                  "filetype": "jpg",
                  "mmsid": "mf3d05c159aa49e1951c5301bc6af1bac",
                  "bytes": 4200,
                  "id": "mf3d05c159aa49e1951c5301bc6af1bac-i0",
                  "digest": "6f15a43f350b49a02e6cdb0dd1863feeaa9f2fc4"
                },
                {
                  "created": "2016-11-03T12:30:18.964000",
                  "filetype": "png",
                  "mmsid": "m84af96809ff8989f3668c93aaaedb69c",
                  "bytes": 206210,
                  "id": "m84af96809ff8989f3668c93aaaedb69c-i0",
                  "digest": "c7e02bad59c63f8616fda84ac1ef689d017fa906"
                }
              ]
            }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->image()->get();

        $this->assertInstanceOf(Paginator::class, $result);

        foreach ($result->getData() as $image) {
            $this->assertInstanceOf(Image::class, $image);
        }
    }

    public function testGetById()
    {
        $jsonToMock = <<<'EOT'
            {
              "created": "2016-11-03T12:30:18.964000",
              "filetype": "png",
              "mmsid": "m84af96809ff8989f3668c93aaaedb69c",
              "bytes": 206210,
              "id": "m84af96809ff8989f3668c93aaaedb69c-i0",
              "digest": "c7e02bad59c63f8616fda84ac1ef689d017fa906"
            }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->image()->getById('m84af96809ff8989f3668c93aaaedb69c-i0');

        $this->assertInstanceOf(Image::class, $result);

        $this->assertSame('2016-11-03T12:30:18.964000', $result->created());
        $this->assertSame('png', $result->filetype());
        $this->assertSame('m84af96809ff8989f3668c93aaaedb69c', $result->mmsid());
        $this->assertSame(206210, $result->bytes());
        $this->assertSame('m84af96809ff8989f3668c93aaaedb69c-i0', $result->id());
        $this->assertSame('c7e02bad59c63f8616fda84ac1ef689d017fa906', $result->digest());
    }

}
