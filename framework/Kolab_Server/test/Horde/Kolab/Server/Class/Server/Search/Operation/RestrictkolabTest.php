<?php
/**
 * Test the search operations restricted to Kolab users.
 *
 * PHP version 5
 *
 * @category Kolab
 * @package  Kolab_Server
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link     http://pear.horde.org/index.php?package=Kolab_Server
 */

/**
 * Require our basic test case definition
 */
require_once dirname(__FILE__) . '/../../../../TestCase.php';

/**
 * Test the search operations restricted to Kolab users.
 *
 * Copyright 2009-2011 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category Kolab
 * @package  Kolab_Server
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link     http://pear.horde.org/index.php?package=Kolab_Server
 */
class Horde_Kolab_Server_Class_Server_Search_Operation_RestrictkolabTest
extends Horde_Kolab_Server_TestCase
{
    public function setUp()
    {
        $this->structure = $this->getMock('Horde_Kolab_Server_Structure_Interface');
    }

    public function testMethodRestrictkolabHasResultRestrictedToKolabUsers()
    {
        $result = $this->getMock('Horde_Kolab_Server_Result_Interface');
        $result->expects($this->once())
            ->method('asArray')
            ->will($this->returnValue(array('a' => 'a')));
        $this->structure->expects($this->once())
            ->method('find')
            ->with(
                $this->isRestrictedToKolabUsers(),
                array('attributes' => 'guid')
            )
            ->will($this->returnValue($result));
        $search = new Horde_Kolab_Server_Search_Operation_Restrictkolab($this->structure);
        $criteria = $this->getMock('Horde_Kolab_Server_Query_Element_Interface');
        $this->assertEquals(array('a'), $search->searchRestrictkolab($criteria));
    }
}