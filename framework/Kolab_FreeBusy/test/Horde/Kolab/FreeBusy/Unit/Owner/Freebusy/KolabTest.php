<?php
/**
 * Test the Kolab freebusy owner.
 *
 * PHP version 5
 *
 * @category   Kolab
 * @package    Kolab_FreeBusy
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link       http://pear.horde.org/index.php?package=Kolab_FreeBusy
 */

/**
 * Prepare the test setup.
 */
require_once dirname(__FILE__) . '/../../../Autoload.php';

/**
 * Test the Kolab freebusy owner.
 *
 * Copyright 2011 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category   Kolab
 * @package    Kolab_FreeBusy
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link       http://pear.horde.org/index.php?package=Kolab_FreeBusy
 */
class Horde_Kolab_FreeBusy_Unit_Owner_Freebusy_KolabTest
extends Horde_Kolab_FreeBusy_TestCase
{
    public function testGetPrimaryId()
    {
        $this->assertEquals(
            'mail@example.org', $this->getOwner()->getPrimaryId()
        );
    }

    public function testGetMail()
    {
        $this->assertEquals(
            'mail@example.org', $this->getOwner()->getMail()
        );
    }

    public function testGetName()
    {
        $this->assertEquals('Test Test', $this->getOwner()->getName());
    }

    public function testByUid()
    {
        $this->assertEquals(
            'Test Test',
            $this->getDb()->getOwner('foo@example.com')->getName()
        );
    }

    public function testByAlias()
    {
        $this->assertEquals(
            'Test Test',
            $this->getDb()->getOwner('alias@example.com')->getName()
        );
    }

    public function testByMailWithoutDomain()
    {
        $this->assertEquals(
            'Test Test',
            $this->getDb()->getOwner('mail', array('domain' => 'example.org'))->getName()
        );
    }

    public function testByMailWithoutDomainButUser()
    {
        $this->assertEquals(
            'Test Test',
            $this->getDb()->getOwner(
                'mail',
                array('user' => new Horde_Kolab_FreeBusy_Stub_User())
            )->getName()
        );
    }

    /**
     * @expectedException Horde_Kolab_FreeBusy_Exception
     */
    public function testByUidWithoutDomain()
    {
        $this->assertEquals(
            'Test Test',
            $this->getDb()->getOwner('foo', array('domain' => 'example.com'))->getName()
        );
    }

    /**
     * @expectedException Horde_Kolab_FreeBusy_Exception
     */
    public function testByAliasWithoutDomain()
    {
        $this->assertEquals(
            'Test Test',
            $this->getDb()->getOwner('alias', array('domain' => 'example.com'))->getName()
        );
    }


    //@todo: getFreeBusy*
}