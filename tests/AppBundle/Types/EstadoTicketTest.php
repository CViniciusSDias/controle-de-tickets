<?php
namespace tests\AppBundle\Types;

use AppBundle\Entity\EstadoTicket\{
    Aberto, AguardandoAprovacao, EmAndamento, Fechado
};
use AppBundle\Types\EstadoTicket;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use PHPUnit\Framework\TestCase;

class EstadoTicketTest extends TestCase
{
    private $mysqlPlatform;
    /** @var  EstadoTicket $estadoTicket */
    private $estadoTicket;

    public function setUp()
    {
        $reflectionEstadoTicket = new \ReflectionClass(EstadoTicket::class);
        $estadoTicket = $reflectionEstadoTicket->newInstanceWithoutConstructor();
        $constructor = $reflectionEstadoTicket->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($estadoTicket);

        $this->estadoTicket = $estadoTicket;
        $this->mysqlPlatform = new MySQL57Platform();
    }

    public function testSqlDeclaration()
    {
        self::assertEquals('INT DEFAULT 1', $this->estadoTicket->getSQLDeclaration([], $this->mysqlPlatform));
    }

    public function testConvertToPHPValue()
    {
        self::assertEquals(new Aberto(), $this->estadoTicket->convertToPHPValue(1, $this->mysqlPlatform));
        self::assertEquals(new EmAndamento(), $this->estadoTicket->convertToPHPValue(2, $this->mysqlPlatform));
        self::assertEquals(new AguardandoAprovacao(), $this->estadoTicket->convertToPHPValue(3, $this->mysqlPlatform));
        self::assertEquals(new Fechado(), $this->estadoTicket->convertToPHPValue(4, $this->mysqlPlatform));
    }

    public function testConvertToDatabaseValue()
    {
        self::assertEquals(1, $this->estadoTicket->convertToDatabaseValue(new Aberto(), $this->mysqlPlatform));
        self::assertEquals(2, $this->estadoTicket->convertToDatabaseValue(new EmAndamento(), $this->mysqlPlatform));
        self::assertEquals(3, $this->estadoTicket->convertToDatabaseValue(new AguardandoAprovacao(), $this->mysqlPlatform));
        self::assertEquals(4, $this->estadoTicket->convertToDatabaseValue(new Fechado(), $this->mysqlPlatform));
    }

    public function testGetName()
    {
        self::assertEquals('estado_ticket', $this->estadoTicket->getName());
    }
}
