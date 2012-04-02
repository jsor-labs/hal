<?php
/**
 * This file is part of the Hal library
 *
 * (c) Ben Longden <ben@nocarrier.co.uk
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Nocarrier
 */

namespace Nocarrier\Tests;

require_once 'src/Nocarrier/Hal.php';
require_once 'src/Nocarrier/HalResource.php';

use \Nocarrier\Hal;
use \Nocarrier\HalResource;

/**
 * HalTest
 * 
 * @package Nocarrier
 * @subpackage Tests
 * @author Ben Longden <ben@nocarrier.co.uk> 
 */
class HalTest extends \PHPUnit_Framework_TestCase
{
    public function testHalResponseReturnsMinimalValidJson()
    {
        $h = new Hal('http://example.com/');
        $this->assertEquals('{"_links":{"self":{"href":"http:\/\/example.com\/"}}}', $h->asJson(false));
    }

    public function testHalResponseReturnsMinimalValidXml()
    {
        $h = new Hal('http://example.com/');
        $this->assertEquals("<?xml version=\"1.0\"?>\n<resource href=\"http://example.com/\"/>\n", $h->asXml(false));
    }

    public function testAddLinkJsonResponse()
    {
        $h = new Hal('http://example.com/');
        $h->addLink('test', '/test/1', 'My Test');

        $result = json_decode($h->asJson());
        $this->assertInstanceOf('StdClass', $result->_links->test);
        $this->assertEquals('/test/1', $result->_links->test->href);
        $this->assertEquals('My Test', $result->_links->test->title);
    }

    public function testAddLinkXmlResponse()
    {
        $h = new Hal('http://example.com/');
        $h->addLink('test', '/test/1', 'My Test');

        $result = new \SimpleXmlElement($h->asXml());
        $data = $result->link->attributes();
        $this->assertEquals('test', $data['rel']);
        $this->assertEquals('/test/1', $data['href']);
        $this->assertEquals('My Test', $data['title']);
    }
}