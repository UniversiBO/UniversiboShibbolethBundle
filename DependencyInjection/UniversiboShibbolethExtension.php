<?php

namespace Universibo\Bundle\ShibbolethBundle\DependencyInjection;

use InvalidArgumentException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Zend\Validator\Uri;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UniversiboShibbolethExtension extends Extension
{
    /**
     * Uri validator
     *
     * @var Uri
     */
    private $validator;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->validator = new Uri();
        $this->validator->setAllowRelative(false);
        $this->validator->setAllowAbsolute(true);
    }
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container,
                new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $baseUrl = $this->validateUrl($config, 'base');
        $container->setParameter('universibo_shibboleth.idp_url.info', $this->validateUrl($config, 'info', $baseUrl));
        $container->setParameter('universibo_shibboleth.idp_url.logout', $this->validateUrl($config, 'logout', $baseUrl));

        $container->setParameter('universibo_shibboleth.route.after_login', $config['route']['after_login']);
        $container->setParameter('universibo_shibboleth.route.after_logout', $config['route']['after_logout']);
        $container->setParameter('universibo_shibboleth.claims', $config['claims']);
        $container->setParameter('universibo_shibboleth.firewall_name', $config['firewall_name']);

        $container->setAlias('universibo_shibboleth.user_provider', $config['user_provider']);
    }

    /**
     * @param  array                    $config
     * @param  string                   $name
     * @param  string                   $base
     * @throws InvalidArgumentException
     * @return string
     */
    private function validateUrl(array $config, $name, $base='')
    {
        $url = $base.$config['idp_url'][$name];
        if (empty($url) || !$this->validator->isValid($url)) {
            throw new InvalidConfigurationException('universibo_shibboleth.idp_url.'.$name.' is not valid!');
        }

        return $url;
    }
}
