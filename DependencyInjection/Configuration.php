<?php

namespace HappyR\Google\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('happy_r_google_api');

        $rootNode
          ->children()
            ->scalarNode('application_name')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('oauth2_client_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('oauth2_client_secret')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('oauth2_redirect_uri')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('developer_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('site_name')->isRequired()->cannotBeEmpty()->end()

            ->scalarNode('authClass')->end()
            ->scalarNode('ioClass')->end()
            ->scalarNode('cacheClass')->end()
            ->scalarNode('basePath')->end()
            ->scalarNode('ioFileCache_directory')->end()
          //end rootnode children
          ->end();

        //let use the api defaults
        //$this->addServicesSection($rootNode);

        return $treeBuilder;
    }


    /**
     * Add the service section
     *
     * @param ArrayNodeDefinition $rootNode
     *
     */
    private function addServicesSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
           ->arrayNode('services')->addDefaultsIfNotSet()->children()
                 ->arrayNode('analytics')->addDefaultsIfNotSet()->children()
                    ->scalarNode('scope')
                        ->defaultValue('https://www.googleapis.com/auth/analytics.readonly')
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('calendar')->addDefaultsIfNotSet()->children()
                    ->arrayNode('scope')
                        ->prototype('scalar')->end()
                        ->isRequired()
                        ->defaultValue(array(
                                  "https://www.googleapis.com/auth/calendar",
                                  "https://www.googleapis.com/auth/calendar.readonly",
                              ))
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('books')->addDefaultsIfNotSet()->children()
                    ->scalarNode('scope')
                        ->defaultValue('https://www.googleapis.com/auth/books')
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('latitude')->addDefaultsIfNotSet()->children()
                    ->arrayNode('scope')
                        ->prototype('scalar')->end()
                        ->isRequired()
                        ->defaultValue(array(
                                  'https://www.googleapis.com/auth/latitude.all.best',
                                  'https://www.googleapis.com/auth/latitude.all.city',
                              ))
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('moderator')->addDefaultsIfNotSet()->children()
                    ->scalarNode('scope')
                        ->defaultValue('https://www.googleapis.com/auth/moderator')
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('oauth2')->addDefaultsIfNotSet()->children()
                    ->arrayNode('scope')
                        ->prototype('scalar')->end()
                        ->isRequired()
                        ->defaultValue(array(
                                  'https://www.googleapis.com/auth/userinfo.profile',
                                  'https://www.googleapis.com/auth/userinfo.email',
                              ))
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('plus')->addDefaultsIfNotSet()->children()
                    ->scalarNode('scope')
                        ->defaultValue('https://www.googleapis.com/auth/plus.me')
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('siteVerification')->addDefaultsIfNotSet()->children()
                    ->scalarNode('scope')
                        ->defaultValue('https://www.googleapis.com/auth/siteverification')
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('tasks')->addDefaultsIfNotSet()->children()
                    ->scalarNode('scope')
                        ->defaultValue('https://www.googleapis.com/auth/tasks')
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

                 ->arrayNode('urlshortener')->addDefaultsIfNotSet()->children()
                    ->scalarNode('scope')
                        ->defaultValue('https://www.googleapis.com/auth/urlshortener')
                        ->cannotBeEmpty()
                    ->end()
                 ->end()->end()

            //end services
            ->end()->end()

        ;
    }
}
