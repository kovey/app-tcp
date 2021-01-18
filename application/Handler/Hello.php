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
use Kovey\Container\Event\Redis;
use Demo\Protobuf\PacketHello;

class Hello extends HandlerAbstract
{
    #[Module\Hello]
    private $hello;

    #[Redis('master')]
    public function world(PacketHello $packet, int $fd) : Array
    {
        return $this->hello->world($packet, $fd);
    }
}
