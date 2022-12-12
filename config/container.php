<?php

use App\Factory\LoggerFactory;
use App\Filesystem\Storage;
use App\Handler\DefaultErrorHandler;
use App\Http\Client\DictionaryApiClient;
use App\Http\Client\DictionaryApiClientFactory;
use Cake\Database\Connection;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use League\Flysystem\Visibility;
use Monolog\Level;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Middleware\ErrorMiddleware;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

return [
    // Application settings
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Register routes
        (require __DIR__ . '/routes.php')($app);

        // Register middleware
        (require __DIR__ . '/middleware.php')($app);

        $app->add(\Slim\Views\TwigMiddleware::createFromContainer($app));

        return $app;
    },

    // HTTP factories
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UploadedFileFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UriFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    // The Slim RouterParser
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

    // The logger factory
    LoggerFactory::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];

        return new LoggerFactory(
            $settings['level'] ?? Level::Debug,
            $settings['path'] ?? 'vfs://root/logs',
            $settings['test'] ?? null
        );
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    EntityManager::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['doctrine'];

        $cache = $settings['dev_mode'] ?
            DoctrineProvider::wrap(new ArrayAdapter()) :
            DoctrineProvider::wrap(new FilesystemAdapter(directory: $settings['cache_dir']));

        $config = Setup::createAttributeMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            null,
            $cache
        );

        return EntityManager::create($settings['connection'], $config);
    },

    'view' => function (ContainerInterface $container) {
        return \Slim\Views\Twig::create(__DIR__ . '/../resources/templates', [
            'cache' => __DIR__ . '/../cache'
        ]);
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['error'];
        $app = $container->get(App::class);

        $logger = $container->get(LoggerFactory::class)
            ->addFileHandler('error.log')
            ->createLogger();

        $errorMiddleware = new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details'],
            $logger
        );

        $errorMiddleware->setDefaultErrorHandler($container->get(DefaultErrorHandler::class));

        return $errorMiddleware;
    },

    Application::class => function (ContainerInterface $container) {
        $application = new Application();

        $application->getDefinition()->addOption(
            new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev')
        );

        foreach ($container->get('settings')['commands'] as $class) {
            $application->add($container->get($class));
        }

        return $application;
    },

    DictionaryApiClientFactory::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['foo_api_client'];

        return new DictionaryApiClientFactory($settings);
    },

    DictionaryApiClient::class => function (ContainerInterface $container) {
        $client = $container->get(DictionaryApiClientFactory::class)->createClient();

        return new DictionaryApiClient($client);
    },

    LocalFilesystemAdapter::class => function () {
        return function (array $config) {
            return new LocalFilesystemAdapter(
                $config['root'] ?? '',
                PortableVisibilityConverter::fromArray(
                    $config['permissions'] ?? [],
                    $config['visibility'] ?? Visibility::PUBLIC
                ),
                $config['lock'] ?? LOCK_EX,
                $config['link'] ?? LocalFilesystemAdapter::DISALLOW_LINKS
            );
        };
    },

    Storage::class => function (ContainerInterface $container) {
        // Read storage adapter settings
        $settings = $container->get('settings')['storage'];
        $adapter = $settings['adapter'];
        $config = $settings['config'];

        // Create filesystem with
        $filesystem = new Filesystem($container->get($adapter)($config));

        return new Storage($filesystem);
    },
];
