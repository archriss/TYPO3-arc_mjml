<?php

namespace Archriss\ArcMjml\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Site\Entity\Site;

class MjmlRouter extends AbstractMjmlMiddleware implements MiddlewareInterface
{

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($this->getTyposcriptFrontendController()->page['doktype'] == $this->doktype && $this->getTyposcriptFrontendController()->type === 0) {
            // We aren't on clean newsletter page, let's redirect
            $redirectToUri = $this->getTyposcriptFrontendController()->cObj->typoLink_URL([
                'parameter' => $this->getTyposcriptFrontendController()->page['uid'] . ',' . $this->typenum,
                // ensure absolute URL is generated when having a valid Site
                'forceAbsoluteUrl' => $GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface
                    && $GLOBALS['TYPO3_REQUEST']->getAttribute('site') instanceof Site
            ]);
            return new RedirectResponse($redirectToUri, 307);
        }
        return $response;
    }
}
