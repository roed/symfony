<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Tests\Session\Storage\Proxy;

use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ForwardCompatTestTrait;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\AbstractProxy;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;

/**
 * Test class for AbstractProxy.
 *
 * @author Drak <drak@zikula.org>
 */
class AbstractProxyTest extends TestCase
{
    use ForwardCompatTestTrait;

    /**
     * @var AbstractProxy
     */
    protected $proxy;

    private function doSetUp()
    {
        $this->proxy = $this->getMockForAbstractClass(AbstractProxy::class);
    }

    private function doTearDown()
    {
        $this->proxy = null;
    }

    public function testGetSaveHandlerName()
    {
        $this->assertNull($this->proxy->getSaveHandlerName());
    }

    public function testIsSessionHandlerInterface()
    {
        $this->assertFalse($this->proxy->isSessionHandlerInterface());
        $sh = new SessionHandlerProxy(new \SessionHandler());
        $this->assertTrue($sh->isSessionHandlerInterface());
    }

    public function testIsWrapper()
    {
        $this->assertFalse($this->proxy->isWrapper());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testIsActive()
    {
        $this->assertFalse($this->proxy->isActive());
        session_start();
        $this->assertTrue($this->proxy->isActive());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testName()
    {
        $this->assertEquals(session_name(), $this->proxy->getName());
        $this->proxy->setName('foo');
        $this->assertEquals('foo', $this->proxy->getName());
        $this->assertEquals(session_name(), $this->proxy->getName());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @expectedException \LogicException
     */
    public function testNameException()
    {
        session_start();
        $this->proxy->setName('foo');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testId()
    {
        $this->assertEquals(session_id(), $this->proxy->getId());
        $this->proxy->setId('foo');
        $this->assertEquals('foo', $this->proxy->getId());
        $this->assertEquals(session_id(), $this->proxy->getId());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @expectedException \LogicException
     */
    public function testIdException()
    {
        session_start();
        $this->proxy->setId('foo');
    }
}
