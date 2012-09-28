<?php

namespace Universibo\Bundle\ShibbolethBundle;

use Universibo\Bundle\ShibbolethBundle\Security\Factory\ShibbolethFactory;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class UniversiboShibbolethBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new ShibbolethFactory());
    }
}
