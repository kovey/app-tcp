<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-05-06 14:43:53
 *
 */
namespace Packet;

use Kovey\Tcp\Protocol\ProtocolInterface;

class Packet implements ProtocolInterface
{
    private int $action;

    private string $message;

    public function __construct(string $body, int $action)
    {
        $this->action = $action;
        $this->message = $body;
    }

    public function getAction() : int
    {
        return $this->action;
    }

    public function getMessage() : string
    {
        return $this->message;
    }
}
