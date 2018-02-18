<?php
/**
 * Cache Driver Provider Tests
 *
 * @package Slab
 * @subpackage Tests
 * @author Eric
 */
namespace Slab\Tests\Cache\Providers;

class PredisTest extends \PHPUnit\Framework\TestCase
{
    /**
     * We're not going to assume a redis server exists for testing so these tests are targeted towards a fail connection interface
     */
    public function testProviderFailedUsage()
    {
        $provider = new \Slab\Cache\Providers\Predis();

        $provider->setCacheKeySuffix('test');
        $this->assertEmpty($provider->get('asdf'));
        $this->assertEmpty($provider->set('asdf', ['data'], 3476));
        $this->assertEmpty($provider->delete('asdf'));
    }
}