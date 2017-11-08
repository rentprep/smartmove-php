<?php

namespace SmartMove\Tests;

use SmartMove\Util;

use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase {

    public function testConvertToSmartMoveObject() {
        $applicationData = [
            [
                'ApplicationId' => '1',
                'Applicants' => ['email@email.com']
            ],
            [
                'ApplicationId' => '2',
                'Applicants' => ['email2@email.com']
            ]
        ];

        $applications = Util::convertToSmartMoveObject($applicationData, \SmartMove\Resource\Application::class);

        $this->assertTrue(is_array($applications));
        $this->assertInstanceOf(\SmartMove\Resource\Application::class, $applications[0]);
    }

    public function testIsList() {
        $list = ['a', 'b', 'c'];
        $this->assertTrue(Util::isList($list));

        $notlist = array('a', 'foo' => 'bar', 'b', 'bar' => 'baz');
        $this->assertFalse(Util::isList($notlist));
    }

    public function testPrepareQueryString() {
        $url = 'http://url.com';
        $params = ['foo' => 'bar', 'bar' => 'baz'];
        $urlWithQueryString = $url . '?foo=bar&bar=baz';
        $this->assertSame($urlWithQueryString, Util::prepareQueryString($url, $params));
    }
}
