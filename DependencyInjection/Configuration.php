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

        $this->configureAccountNode($rootNode);

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

    /**
     * Add properties and validation for account configuration.
     *
     * @param ArrayNodeDefinition $node
     */
    private function configureAccountNode(ArrayNodeDefinition $node)
    {
        $node
            ->beforeNormalization()
                ->ifTrue(function ($v) { return is_array($v) && !array_key_exists('accounts', $v) && !array_key_exists('account', $v); })
                ->then(function ($v) {
                    // Key that should not be rewritten to the account config
                    $excludedKeys = array('default_account' => true);
                    $account = array();

                    foreach ($v as $key => $value) {
                        if (isset($excludedKeys[$key])) {
                            continue;
                        }

                        $account[$key] = $v[$key];
                        unset($v[$key]);
                    }

                    $v['default_account'] = isset($v['default_account']) ? (string) $v['default_account'] : 'default';
                    $v['accounts'] = array($v['default_account'] => $account);

                    return $v;
                })
            ->end()
            ->children()
                ->scalarNode('default_account')->cannotBeEmpty()->defaultValue('default')->end()
            ->end()
            ->fixXmlConfig('account')
            ->children()
                ->arrayNode('accounts')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->validate()
                            ->always(
                                function ($v) {
                                    $required = array();

                                    switch ($v['type']) {
                                        case 'web':
                                            $required = array('oauth2_client_secret', 'oauth2_redirect_uri', 'developer_key', 'site_name');
                                            break;

                                        case 'service':
                                            if ((isset($v['getenv']) && true === $v['getenv']) || isset($v['json_file'])) {
                                                return $v;
                                            }

                                            $required = array('oauth2_client_email', 'oauth2_private_key', 'oauth2_scopes');
                                            break;
                                    }

                                    foreach ($required as $key) {
                                        if (!isset($v[$key]) || empty($v[$key])) {
                                            throw new \InvalidArgumentException(sprintf('"%s" is not set or empty', $key));
                                        }
                                    }

                                    return $v;
                                }
                            )
                        ->end()
                        ->children()
                            ->enumNode('type')->values(array('web', 'service'))->cannotBeEmpty()->defaultValue('web')->end()
                            ->scalarNode('application_name')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('oauth2_client_id')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('oauth2_client_secret')->end()
                            ->scalarNode('oauth2_client_email')->end()
                            ->scalarNode('oauth2_private_key')->end()
                            ->scalarNode('oauth2_redirect_uri')->end()
                            ->variableNode('oauth2_scopes')->end()
                            ->scalarNode('developer_key')->end()
                            ->scalarNode('site_name')->end()
                            ->scalarNode('getenv')->end()
                            ->scalarNode('json_file')->end()

                            ->scalarNode('authClass')->end()
                            ->scalarNode('ioClass')->end()
                            ->scalarNode('cacheClass')->end()
                            ->scalarNode('basePath')->end()
                            ->scalarNode('ioFileCache_directory')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
