<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\FlatXmlDataSet;
use DElfimov\Session\Session;

/**
 * @covers DElfimov\Session\Session
 */
class SessionTest extends TestCase
{
    use TestCaseTrait;

    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
                self::$pdo->exec(file_get_contents(__DIR__ . '/../sessions.sql'));
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo);
        }
        return $this->conn;
    }

    /**
     * @return FlatXmlDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/sessions.xml');
    }

    public function testGetRowCount()
    {
        $this->assertEquals(3, $this->getConnection()->getRowCount('sessions'));
    }

    public function testCanBeCreated()
    {
        $session = $this->getSession();
        $this->assertEquals(true, $session instanceof Session);
    }

    private function getSession()
    {
        $session = new DElfimov\Session\Session(
            new DElfimov\Session\Handlers\PDOHandler(
                $this->getConnection()->getConnection()
            )
        );
        return $session;
    }

    public function keyValueProvider()
    {
        return [
            ['a', 'value'],
            ['b', 'value'],
            ['c', 'value'],
            ['really long string as it is could be even if it\'s not necessary to be this stupidely long', 'value'],
            ['8391', 'value'],
            ['^&* #@', 'value'],
        ];
    }
}
