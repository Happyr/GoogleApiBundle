<?php

namespace HappyR\Google\ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader;

class HappyRGoogleApiExtension extends ConfigurableExtension
{
    /**
     * {@inheritDoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        foreach ($config['accounts'] as $name => $account) {
            $this->loadAccount($name, $account, $container);
        }

        // Backwards compatibility
        $default = $config['default_account'];
        $container->setParameter('happy_r_google_api', array_merge($config['accounts'][$default], $config));
        $container->setAlias('happyr.google.api.client', sprintf('happyr.google.api.%s_client', $default));
        $container->setAlias('happyr.google.api.analytics', sprintf('happyr.google.api.%s_analytics', $default));
        $container->setAlias('happyr.google.api.youtube', sprintf('happyr.google.api.%s_youtube', $default));
    }

    /**
     * Define services for each account configuration.
     *
     * @param string           $name      The account name
     * @param array            $config    The account configuration
     * @param ContainerBuilder $container The container builder
     */
    public function loadAccount($name, array $config, ContainerBuilder $container)
    {
        $clientId = sprintf('happyr.google.api.%s_client', $name);
        $client   = new Definition(
            'HappyR\Google\ApiBundle\Services\GoogleClient',
            array($config, new Reference('logger', ContainerInterface::IGNORE_ON_INVALID_REFERENCE))
        );

        $client->addTag('monolog.logger', array('channel' => 'google_client'));

        $container->setDefinition($clientId, $client);

        $container->setDefinition(
            sprintf('happyr.google.api.%s_analytics', $name),
            new Definition(
                'HappyR\Google\ApiBundle\Services\AnalyticsService',
                array(new Reference($clientId))
            )
        );

        $container->setDefinition(
            sprintf('happyr.google.api.%s_youtube', $name),
            new Definition(
                'HappyR\Google\ApiBundle\Services\YoutubeService',
                array(new Reference($clientId))
            )
        );

        $container->setDefinition(
            sprintf('happyr.google.api.%s_groups_migration', $name),
            new Definition(
                'HappyR\Google\ApiBundle\Services\GroupsMigrationService',
                array(new Reference($clientId))
            )
        );
    }
}
