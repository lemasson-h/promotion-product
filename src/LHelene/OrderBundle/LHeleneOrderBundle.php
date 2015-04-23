<?php

namespace LHelene\OrderBundle;

use LHelene\OrderBundle\DependencyInjection\Compiler\PromotionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LHeleneOrderBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new PromotionCompilerPass());
    }
}
