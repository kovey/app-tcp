<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-01-13 14:47:38
 *
 */
namespace Handler;

use Module;
use Kovey\Tcp\Handler\HandlerAbstract;
use Demo\Protobuf\PacketHello;
use Kovey\Container\Event\Protocol;

class Hello extends HandlerAbstract
{
    #[Module\Hello]
    private $hello;

    #[Protocol(1001, PacketHello::class)]
    public function world(PacketHello $packet, int $fd) : Array
    {
        return $this->hello->world($packet, $fd);
    }
}
