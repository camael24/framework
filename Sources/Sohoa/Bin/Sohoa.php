<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 16/01/14
 * Time: 16:57
 */
namespace Sohoa\Bin {
    use Hoa\Core\Core;
    use Sohoa\Bin\Style\Basic;

    require __DIR__ . '/../../../../../autoload.php';
    require __DIR__ . DS . 'Style' . DS . 'Basic.php';
    $style = new Basic();
    $style->import();

    /**
     * Here we go…
     */
    try {

        $core       = Core::getInstance();
        $parameters = $core->getParameters();
        /**
         * @var \Hoa\Core\Parameter\Parameter $parameters
         */
        $cwd =  __DIR__ . '/../../../../../../';
        $parameters->setKeyword('cwd', realpath($cwd));
        $parameters->setParameter('protocol.Application', '(:cwd:)/Application/');
        $parameters->setParameter('protocol.Public', '(:%root.application:)/Public/');
        $parameters->setParameter('namespace.prefix.Application', '(:cwd:)/');

        $core->setProtocol();

        $router = new \Hoa\Router\Cli();
        $router->get(
            'g',
            '(?:(?<vendor>\w+)\s+)?(?<library>\w+)?(?::(?<command>\w+))?(?<_tail>.*?)',
            'main',
            'main',
            array(
                'vendor'  => 'sohoa',
                'library' => 'core',
                'command' => 'welcome'
            )
        );

        $dispatcher = new \Hoa\Dispatcher\Basic(array(
            'synchronous.controller'
            => '(:%variables.vendor:lU:)\Bin\Command\(:%variables.library:lU:)\(:%variables.command:lU:)',
            'synchronous.action'
            => 'main'
        ));

        $dispatcher->setKitName('Hoa\Console\Dispatcher\Kit');
        exit($dispatcher->dispatch($router));
    } catch (\Hoa\Core\Exception $e) {

        $message = $e->raise(true);
    } catch (\Exception $e) {

        $message = $e->getMessage();
    }

    \Hoa\Console\Cursor::colorize('foreground(white) background(red)');
    echo $message, "\n";
    \Hoa\Console\Cursor::colorize('normal');
}
