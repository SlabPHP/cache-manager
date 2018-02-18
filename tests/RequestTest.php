<?php
/**
 * Cache Driver Request Tests
 *
 * @package Slab
 * @subpackage Tests
 * @author Eric
 */
namespace Slab\Tests\Cache;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test creation
     */
    public function testCreationAndCallback()
    {
        $request = new \Slab\Cache\Request();

        $request
            ->setCacheKey('dummy')
            ->setCacheTTL(3600)
            ->setCallback($this, 'someCallBackFunction', ['value1'])
            ->setForceRefresh(true);

        $this->assertEquals('dummy', $request->getCacheKey());
        $this->assertEquals(3600, $request->getCacheTTL());
        $this->assertEquals('value1', $request->executeCallback());
    }

    /**
     * @param $parameters
     * @return string
     */
    public function someCallBackFunction($parameters)
    {
        return $parameters;
    }
}