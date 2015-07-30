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
        $container->setAlias('happyr.google.api.groups_migration', sprintf('happyr.google.api.%s_groups_migration', $default));
        $container->setAlias('happyr.google.api.slides', sprintf('happyr.google.api.%s_slides', $default));
        $container->setAlias('happyr.google.api.drive', sprintf('happyr.google.api.%s_drive', $default));
    }

    /**
     * Define Google Client and add services for each account configuration.
     *
     * @param string           $name      The account name
     * @param array            $config    The account configuration
     * @param ContainerBuilder $container The container builder
     */
    private function loadAccount($name, array $config, ContainerBuilder $container)
    {
        $clientId = sprintf('happyr.google.api.%s_client', $name);

        $client = new Definition(
            'HappyR\Google\ApiBundle\Services\GoogleClient',
            array($config, new Reference('logger', ContainerInterface::IGNORE_ON_INVALID_REFERENCE))
        );

        $client->addTag('monolog.logger', array('channel' => 'google_client'));

        $container->setDefinition($clientId, $client);

        $this->createServices($name, $clientId, $container);
    }

    /**
     * Create Google services for an account.
     *
     * @param                  $name
     * @param                  $clientId
     * @param ContainerBuilder $container
     */
    private function createServices($name, $clientId, ContainerBuilder $container)
    {
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

        $container->setDefinition(
            sprintf('happyr.google.api.%s_slides', $name),
            new Definition(
                'HappyR\Google\ApiBundle\Services\SlidesService',
                array(new Reference($clientId))
            )
        );

        $container->setDefinition(
            sprintf('happyr.google.api.%s_drive', $name),
            new Definition(
                'HappyR\Google\ApiBundle\Services\DriveService',
                array(new Reference($clientId))
            )
        );
    }
}
