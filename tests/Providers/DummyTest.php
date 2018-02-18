<?php
/**
 * Cache Driver Provider Tests
 *
 * @package Slab
 * @subpackage Tests
 * @author Eric
 */
namespace Slab\Tests\Cache\Providers;

class DummyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test creation
     */
    public function testProvider()
    {
        $dummy = new \Slab\Cache\Providers\Dummy();

        $this->assertNull($dummy->get('whatever'));
        $this->assertTrue($dummy->set('key', [], 346));
        $this->assertTrue($dummy->delete('something'));
    }
}