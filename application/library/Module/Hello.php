<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-01-13 14:49:41
 *
 */
namespace Module;

use Demo\Protobuf\PacketHello;
use Kovey\Library\Util\Json;

#[\Attribute]
class Hello
{
    public function world(PacketHello $packet, int $fd)
    {
        $this->redis->hSet('kovey_tcp', 'name', $packet->getName());
        $this->redis->hSet('kovey_tcp', 'userId', $packet->getUserId());
        $labels = array();
        foreach ($packet->getLabels() as $label) {
            $labels[] = $label;
        }
        $this->redis->hSet('kovey_tcp', 'labels', Json::encode($labels));

        $result = $this->redis->hGetAll('kovey_tcp');

        $res = new PacketHello();
        $res->setName(empty($result['name']) ? '' : $result['name'])
            ->setUserId(empty($result['userId']) ? 0 : $result['userId'])
            ->setLabels(Json::decode(empty($result['labels']) ? '[]' : $result['labels']));

        return array(
            'message' => $res,
            'action' => 2001
        );
    }
}
