<?php
/**
 * Cache Driver Tests
 *
 * @package Slab
 * @subpackage Tests
 * @author Eric
 */
namespace Slab\Tests\Cache;

class DriverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test initialization
     */
    public function testDriverInitializeAndExecute()
    {
        $mockProvider = new Mocks\Provider();

        $cache = new \Slab\Cache\Driver();

        $cache
            ->setProvider($mockProvider);

        $this->assertEquals($mockProvider, $cache->getProvider());

        $this->assertEquals('blargh', $cache->get('blargh'));
        $this->assertEquals(true, $cache->set('blargh', ['something'], 546));
        $this->assertEquals(true, $cache->delete('blargh'));
    }
}