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

#[\Attribute]
class Hello
{
    public function world(PacketHello $packet, int $fd)
    {
        $labels = array();
        foreach ($packet->getLabels() as $label) {
            $labels[] = $label;
        }

        $result = array(
            'name' => $packet->getName(),
            'userId' => $packet->getUserId(),
            'labels' => $labels
        );

        $res = new PacketHello();
        $res->setName(empty($result['name']) ? '' : $result['name'])
            ->setUserId(empty($result['userId']) ? 0 : $result['userId'])
            ->setLabels($result['labels']);

        return array(
            'message' => $res,
            'action' => 2001
        );
    }
}
