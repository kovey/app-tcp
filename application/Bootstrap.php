<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-01-13 14:47:10
 *
 */
use Kovey\Rpc\Server\Port;
use Kovey\Rpc\App\AppBase;
use Kovey\Rpc\App\Bootstrap\Autoload;
use Kovey\Library\Exception\ProtocolException;
use Kovey\Container\Event;
use Kovey\Tcp\Event as TE;
use Kovey\App\Event as AE;
use Packet\Protobuf;
use Demo\Protobuf\Error;
use Kovey\Library\Config\Manager;

class Bootstrap
{
    public function __initRpc($app)
    {
        $port = new Port($app->getServer()->getServ(), Manager::get('server.rpc'));
        $autoload = new Autoload();
        $autoload->register();
        $appBase = new AppBase(Manager::get('server'));
        $appBase->registerServer($port)
                ->registerAutoload($autoload)
                ->registerContainer($app->getContainer());
        $app->registerOtherApp('rpc', $appBase);
    }

	public function __initEvents($app)
	{
		$app->on('run_handler', function (TE\RunHandler $event) {
			try {
				return call_user_func(array($event->getHandler(), $event->getMethod()), $event->getMessage(), $event->getFd());
			} catch (ProtocolException $e) {
                Logger::writeExceptionLog(__LINE__, __FILE__, $e, $handler->traceId);
                $error = new Error();
                $error->setMsg($e->getMessage())
                    ->setCode($e->getCode());
                return array(
                    'action' => 500,
                    'message' => $error
                );
			} catch (BusiException $e) {
                Logger::writeExceptionLog(__LINE__, __FILE__, $e, $handler->traceId);
                $error = new Error();
                $error->setMsg($e->getMessage())
                    ->setCode($e->getCode());
                return array(
                    'action' => 500,
                    'message' => $error
                );
			}
        })
	    ->on('error', function (TE\Error $event) {
            $msg = $event->getError();
            $error = new Error();
            if ($msg instanceof \Throwable) {
                $error->setMsg($msg->getMessage())
                    ->setCode($msg->getCode());
            } else {
                $error->setMsg($msg)
                    ->setCode(1000);
            }

            return array(
                'action' => 500,
                'message' => $error
            );
		})
        ->on('monitor', function (AE\Monitor $event) {
            // monitor process
        })
        ->serverOn('error', function (TE\Error $event) {
            $msg = $event->getError();
            $error = new Error();
            if ($msg instanceof \Throwable) {
                $error->setMsg($msg->getMessage())
                    ->setCode($msg->getCode());
            } else {
                $error->setMsg($msg)
                    ->setCode(1000);
            }

            return array(
                'action' => 500,
                'message' => $error
            );
        })
        ->serverOn('pack', function (TE\Pack $event) use ($app) {
            return Protobuf::pack($event->getPacket(), $event->getAction());
        })
        ->serverOn('unpack', function (TE\Unpack $event) {
            return Protobuf::unpack($event->getData());
        })
        ->serverOn('close', function (TE\Close $event) use ($app) {
            // some code here
        })
        ->serverOn('connect', function (TE\Connect $event) use ($app) {
            // some code here
        });
	}
}
