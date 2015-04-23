<?php
namespace LHelene\OrderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class PromotionCompilerPass implements CompilerPassInterface
{
    const METHOD_CALLED     = 'addPromotion';
    const PROMOTION_SERVICE = 'lhelene.order.service.promotion';
    const PROMOTION_TAG     = 'lhelene.order.promotion';
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::PROMOTION_SERVICE)) {
            return;
        }
        $definition = $container->getDefinition(self::PROMOTION_SERVICE);
        foreach ($container->findTaggedServiceIds(self::PROMOTION_TAG) as $id => $attributes) {
            $definition->addMethodCall(
                       self::METHOD_CALLED,
                       array(new Reference($id))
            );
        }
    }
}
