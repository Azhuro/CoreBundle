<?php
/**
 * (c) Johnny Cottereau <johnny.cottereau@gmail.com>
 */
namespace Azhuro\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ResolveDoctrineTargetEntitiesPass
 * @package Azhuro\Bundle\CoreBundle\DependencyInjection\Compiler
 */
class ResolveDoctrineTargetEntitiesPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $interfaces;

    /**
     * ResolveDoctrineTargetEntitiesPass constructor.
     * @param array $interfaces
     */
    public function __construct(array $interfaces)
    {
        $this->interfaces = $interfaces;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('doctrine.orm.listeners.resolve_target_entity')) {
            throw new \RuntimeException('Cannot find Doctrine Target Entity Resolver Listener.');
        }

        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        foreach ($this->interfaces as $interface => $model) {
            $resolveTargetEntityListener->addMethodCall('addResolveTargetEntity', [
                $this->getInterface($container, $interface),
                $this->getClass($container, $model),
                [],
            ]);
        }

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_listener')) {
            $resolveTargetEntityListener->addTag('doctrine.event_listener', array('event' => 'loadClassMetadata'));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param $key
     * @return string
     */
    private function getClass(ContainerBuilder $container, $key)
    {
        if ($container->hasParameter($key)) {
            return $container->getParameter($key);
        }
        if (class_exists($key)) {
            return $key;
        }
        throw new \InvalidArgumentException(
            sprintf('The class %s does not exist.', $key)
        );
    }

    /**
     * @param ContainerBuilder $container
     * @param $interface
     * @return string
     */
    private function getInterface(ContainerBuilder $container, $interface)
    {
        return $interface;
    }
}