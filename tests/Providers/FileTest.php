<?php
/**
 * Cache Driver Provider Tests
 *
 * @package Slab
 * @subpackage Tests
 * @author Eric
 */
namespace Slab\Tests\Cache\Providers;

class FileTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test provider
     *
     * @throws \Exception
     */
    public function testProvider()
    {
        $cacheDir = '/tmp/';

        $fileProvider = new \Slab\Cache\Providers\File();

        $fileProvider
            ->setCacheDirectory($cacheDir);

        $fileProvider->set('thing', 'special value', 3600);
        $this->assertFileExists($cacheDir . 'thing~' . php_sapi_name());

        $this->assertEquals('special value', $fileProvider->get('thing'));
        $fileProvider->delete('thing');

        $this->assertFileNotExists($cacheDir . 'thing~' . php_sapi_name());
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidDirectory()
    {
        $fileProvider = new \Slab\Cache\Providers\File();

        $fileProvider
            ->setCacheDirectory('/blargh/frufru32 asdf');
    }
}