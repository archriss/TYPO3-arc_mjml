<?php

namespace Archriss\ArcMjml\Middleware;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

abstract class AbstractMjmlMiddleware
{

    /**
     * @var int 
     */
    protected $doktype = 0;

    /**
     * @var int 
     */
    protected $typenum = 0;

    /**
     * Initialize Middleware configuration
     */
    public function __construct()
    {
        $this->doktype = (int)GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('arc_mjml', 'mjml/doktype') ?: 160;
        $this->typenum = (int)GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('arc_mjml', 'mjml/typenum') ?: 15;
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTyposcriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    /**
     * @return \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected function getContentObjectRenderer(): ContentObjectRenderer
    {
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObject->start([], '');
        return $contentObject;
    }
}