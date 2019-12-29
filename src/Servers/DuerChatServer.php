<?php

namespace Commune\Platform\DuerOS\Servers;


use Commune\Chatbot\Blueprint\Application;
use Commune\Platform\DuerOS\DuerOSComponent;
use Commune\Hyperf\Foundations\Drivers\StdConsoleLogger;
use Commune\Hyperf\Foundations\Options\AppServerOption;
use Hyperf\Contract\OnRequestInterface;
use Hyperf\Server\ServerFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;

class DuerChatServer implements OnRequestInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Application
     */
    protected $chatApp;

    /**
     * @var AppServerOption
     */
    protected $botOption;

    /**
     * @var DuerOSComponent
     */
    protected $duerOSOption;

    /**
     * @var \Swoole\Server
     */
    protected $swooleServer;

    /**
     * @var string
     */
    protected $privateKeyContent;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * DuerOSServer constructor.
     * @param StdConsoleLogger $logger
     */
    public function __construct(ContainerInterface $container, Application $chatApp, AppServerOption $botOption)
    {
        $this->chatApp = $chatApp;
        $this->botOption = $botOption;
        $this->container = $container;
        $this->swooleServer = $this->container
            ->get(ServerFactory::class)
            ->getServer()
            ->getServer();

        $this->duerOSOption =$this->chatApp->getProcessContainer()->get(DuerOSComponent::class);

        $this->logger = $this->chatApp
            ->getProcessContainer()
            ->get(LoggerInterface::class);
    }

    protected function getPrivateKeyContent() : string
    {
        if (isset($this->privateKeyContent)) {
            return $this->privateKeyContent;
        }

        $keyFile = $this->duerOSOption->privateKey;

        if (empty($keyFile)) {
            $this->chatApp->getConsoleLogger()->warning('dueros openssl verify with empty private key');
            return $this->privateKeyContent = '';
        }

        if (!file_exists($keyFile)) {
            $this->chatApp->getConsoleLogger()->error('dueros openssl private key file '.$keyFile .' not exists!');
            return $this->privateKeyContent = '';
        }

        $this->privateKeyContent = file_get_contents($keyFile);
        return $this->privateKeyContent;
    }

    public function onRequest(SwooleRequest $request, SwooleResponse $response): void
    {
        $method = $request->server['request_method'] ?? null;
        // 技能探活.
        if ($method === 'HEAD') {
            $response->status(204);
            $response->end();
            $this->logger->info('receive dueros exploration, response status 204');
            return;
        }

        $chatbotRequest = $this->generateRequest($request, $response);

        try {
            $this->chatApp->getKernel()->onUserMessage($chatbotRequest);
            $response->end();

        } catch (\Throwable $e) {
            $this->chatApp->getConsoleLogger()->critical((string) $e);
            $response->status(500);
            $response->end('failure');
        }
    }


    protected function generateRequest(SwooleRequest $request, SwooleResponse $response) : DuerChatRequest
    {
        $privateKeyContent = $this->getPrivateKeyContent();
        return new DuerChatRequest(
            $this->botOption,
            $this->duerOSOption,
            $this->swooleServer,
            $this->chatApp->getProcessContainer()[LoggerInterface::class],
            $request,
            $response,
            DuerChatRequest::fetchRawInputOfRequest($request),
            $privateKeyContent
        );
    }


}