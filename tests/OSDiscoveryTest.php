<?php
/**
 * DiscoveryTest.php
 *
 * -Description-
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    LibreNMS
 * @link       http://librenms.org
 * @copyright  2016 Tony Murray
 * @author     Tony Murray <murraytony@gmail.com>
 */

namespace LibreNMS\Tests;

include 'tests/mocks/mock.snmp.inc.php';

class DiscoveryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Set up variables and include os discovery
     *
     * @param string $expectedOS the OS to test for
     * @param string $sysDescr set the snmp sysDescr variable
     * @param string $sysObjectId set the snmp sysObjectId variable
     * @param array $mockSnmp set arbitrary snmp variables with an associative array
     * @param array $device device array to send
     */
    private function checkOS($expectedOS, $sysDescr = '', $sysObjectId = '', $mockSnmp = array(), $device = array())
    {
        global $config;
        setSnmpMock($mockSnmp);
        $os = null;

        // cannot use getHostOS() because of functions.php includes
        $pattern = $config['install_dir'] . '/includes/discovery/os/*.inc.php';
        foreach (glob($pattern) as $file) {
            include $file;
            if (isset($os)) {
                break;
            }
        }

        $this->assertEquals($expectedOS, $os);
    }

    public function testAiros()
    {
        $this->checkOS('airos', 'Linux', '.1.3.6.1.4.1.10002.1');
        $this->checkOS('airos', 'Linux', '.1.3.6.1.4.1.41112.1.4');

        $mockSnmp = array(
            'dot11manufacturerName.5' => 'Ubiquiti',
        );
        $this->checkOS('airos', 'Linux', '', $mockSnmp);
    }

    public function testAirosAf()
    {
        $mockSnmp = array(
            'fwVersion.1' => '1.0',
        );
        $this->checkOS('airos-af', 'Linux', '.1.3.6.1.4.1.10002.1', $mockSnmp);
    }

    public function testApc()
    {
        $this->checkOS('apc', 'APC Web/SNMP Management Card (MB:v3.9.2 PF:v3.5.9 PN:apc_hw03_aos_359.bin AF1:v3.5.6 AN1:apc_hw03_nb200_356.bin MN:NBRK0200 HR:05 SN: FFFFFFFFFFFF MD:07/07/2012)', '.1.3.6.1.4.1.318.1.3.8.4');
        $this->checkOS('apc', 'APC Switched Rack PDU');
        $this->checkOS('apc', 'APC MasterSwitch PDU');
        $this->checkOS('apc', 'APC Metered Rack PDU');
    }

    public function testAxiscam()
    {
        $this->checkOS('axiscam', ' ; AXIS 221; Network Camera; 4.30; Nov 29 2005 11:18; 141; 1;');
        $this->checkOS('axiscam', ' ; AXIS M7011; Network Video Encoder; 5.75.1; Mar 04 2015 10:10; 1FC; 1;');
    }

    public function testCiscosmblinux()
    {
        $this->checkOS('ciscosmblinux', 'Linux Cisco Small Business');
    }

    public function testCumulus()
    {
        $this->checkOS('cumulus', 'Linux', '.1.3.6.1.4.1.40310');
    }

    public function testDdnos()
    {
        $mockSnmp = array(
            'SFA-INFO::systemName.0' => 1,
        );
        $this->checkOS('ddnos', 'Linux', '', $mockSnmp);
    }

    public function testDsm()
    {
        $mockSnmp = array(
            'HOST-RESOURCES-MIB::hrSystemInitialLoadParameters.0' => 'syno_hw_version',
        );
        $this->checkOS('dsm', 'Linux', '', $mockSnmp);
    }

    public function testEatonups()
    {
        $this->checkOS('eatonups', 'Eaton 5P 2200');
        $this->checkOS('eatonups', 'Eaton 5PX 2000');
    }

    public function testEndian()
    {
        $this->checkOS('endian', 'Linux endian');
    }

    public function testLinux()
    {
        $this->checkOS('linux', 'Linux');
    }

    public function testNetbotz()
    {
        $this->checkOS('netbotz', 'Linux', '.1.3.6.1.4.1.5528.100.20.10.2014');
        $this->checkOS('netbotz', 'Linux', '.1.3.6.1.4.1.5528.100.20.10.2016');
    }

    public function testNios()
    {
        $this->checkOS('nios', 'Linux 3.14.25 #1 SMP Thu Jun 16 18:19:37 EDT 2016 x86_64', '.1.3.6.1.4.1.7779.1.1402');
        $this->checkOS('nios', 'IPAM', '.1.3.6.1.4.1.7779.1.1004');
    }

    public function testPcoweb()
    {
        $mockSnmp = array(
            'roomTemp.0' => 1,
        );
        $this->checkOS('pcoweb', 'Linux', '', $mockSnmp);
    }

    public function testPktj()
    {
        $mockSnmp = array(
            'GANDI-MIB::rxCounter.0' => 1,
        );
        $this->checkOS('pktj', 'Linux', '', $mockSnmp);
    }

    public function testProcera()
    {
        $this->checkOS('procera', 'Linux', '.1.3.6.1.4.1.15397.2');
    }

    public function testQnap()
    {
        $mockSnmp = array(
            'ENTITY-MIB::entPhysicalMfgName.1' => 'QNAP',
        );
        $this->checkOS('qnap', 'Linux', '', $mockSnmp);
    }

    public function testSophos()
    {
        $this->checkOS('sophos', 'Linux g56fa85e');
        $this->checkOS('sophos', 'Linux gc80f187');
        $this->checkOS('sophos', 'Linux g829be90');
        $this->checkOS('sophos', 'Linux g63c0044');
    }

    public function testUnifi()
    {
        $mockSnmp = array(
            'dot11manufacturerProductName.6' => 'UAP',
        );
        $this->checkOS('unifi', 'Linux', '.1.3.6.1.4.1.10002.1', $mockSnmp);

        $mockSnmp = array(
            'dot11manufacturerProductName.4' => 'UAP-PRO',
        );
        $this->checkOS('unifi', 'Linux', '.1.3.6.1.4.1.10002.1', $mockSnmp);

        $mockSnmp = array(
            'dot11manufacturerProductName.0' => 'UAP-AC2',
        );
        $this->checkOS('unifi', 'Linux', '.1.3.6.1.4.1.10002.1', $mockSnmp);
    }

    public function testZxr10()
    {
        $this->checkOS('zxr10', 'ZTE Ethernet Switch  ZXR10 5250-52TM-H, Version: V2.05.11B23');
    }
}
