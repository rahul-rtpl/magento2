<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Console\Test\Unit\Command;

use Magento\Setup\Console\Command\MaintenanceAllowIpsCommand;
use Symfony\Component\Console\Tester\CommandTester;

class MaintenanceAllowIpsCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\App\MaintenanceMode|\PHPUnit_Framework_MockObject_MockObject
     */
    private $maintenanceMode;

    /**
     * @var MaintenanceAllowIpsCommand
     */
    private $command;

    public function setUp()
    {
        $this->maintenanceMode = $this->getMock('Magento\Framework\App\MaintenanceMode', [], [], '', false);
        $this->command = new MaintenanceAllowIpsCommand($this->maintenanceMode);
    }

    /**
     * @param array $input
     * @param string $expectedMessage
     * @dataProvider executeDataProvider
     */
    public function testExecute(array $input, $expectedMessage)
    {
        if (isset($input['--none']) && !$input['--none'] && isset($input['ip'])) {
            $this->maintenanceMode
                ->expects($this->once())
                ->method('getAddressInfo')
                ->willReturn(explode(',', $input['ip']));
        }
        $tester = new CommandTester($this->command);
        $tester->execute($input);
        $this->assertEquals($expectedMessage, $tester->getDisplay());

    }

    /**
     * return array
     */
    public function executeDataProvider()
    {
        return [
            [
                ['ip' => '127.0.0.1,127.0.0.2', '--none' => false],
                'Set exempt IP-addresses: 127.0.0.1, 127.0.0.2' . PHP_EOL
            ],
            [
                ['--none' => true],
                'Set exempt IP-addresses: none' . PHP_EOL
            ],
            [
                ['ip' => '127.0.0.1,127.0.0.2', '--none' => true],
                'Set exempt IP-addresses: none' . PHP_EOL
            ],
            [
                [],
                ''
            ]
        ];
    }
}
