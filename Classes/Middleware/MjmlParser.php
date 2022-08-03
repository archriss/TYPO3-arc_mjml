<?php

namespace Archriss\ArcMjml\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MjmlParser extends AbstractMjmlMiddleware implements MiddlewareInterface
{

    /**
     * @var string
     */
    protected $binaryPath = '';

    /**
     * @var string
     */
    protected $configPath = '';

    /**
     * Initialize Middleware configuration
     */
    public function __construct()
    {
        parent::__construct();
        $this->binaryPath = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('arc_mjml', 'mjml/binaryPath') ?: 'node_modules/.bin/mjml';
        $this->configPath = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('arc_mjml', 'mjml/configPath') ?: 'boilerplate-mjml';
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        // Check if we need to parse mjml to html
        if ($this->getTyposcriptFrontendController()->page['doktype'] === $this->doktype && $this->getTyposcriptFrontendController()->type === $this->typenum) {
            $binPath = $this->getBinPath();
            if (!is_null($binPath)) {
                // Get temporary filename in order to work on it
                $tempName = GeneralUtility::tempnam('newsletter-' . $this->getTyposcriptFrontendController()->page['uid'], '.mjml');

                // write content to file
                GeneralUtility::writeFile(
                    $tempName,
                    $this->getTyposcriptFrontendController()->content
                );

                // Convert file and get the output
                $content = '';
                CommandUtility::exec(
                    $binPath . ' ' . $tempName . $this->getMjmlOptions(),
                    $content
                );

                // Remove temporary file
                GeneralUtility::unlink_tempfile($tempName);
                clearstatcache();

                // Return new Response
                return $this->getMjmlResponse($content);
            }
        }
        return $response;
    }

    /**
     * @return string
     */
    protected function getMjmlPath(): string
    {
        return
            Environment::getProjectPath() .
            '/' . trim($this->configPath, '/') . '/';
    }

    /**
     * @return string
     */
    protected function getMjmlOptions(): string
    {
        $options = ' --noStdoutFileComment';
        $options.= ' --config.mjmlConfigPath ' . $this->getMjmlPath();
        return $options;
    }

    /**
     * @return string|null
     */
    protected function getBinPath(): ?string
    {
        $path = Environment::getProjectPath();
        $path.= '/' . trim($this->binaryPath, '/');
        if (file_exists($path)) {
            return $path;
        } else {
            return null;
        }
    }

    /**
     * @param array $content
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getMjmlResponse(array $content): ResponseInterface
    {
        $response = GeneralUtility::makeInstance(Response::class);
        $response->getBody()->write(implode(LF, $content));
        return $response;
    }
}
