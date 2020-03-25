<?php

namespace AmpProject;

use AmpProject\Dom\Document;
use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Helper functionality to deal with AMP dev-mode.
 *
 * @link    https://github.com/ampproject/amphtml/issues/20974
 *
 * @package ampproject/common
 */
final class DevMode
{

    /**
     * Attribute name for AMP dev mode.
     *
     * @var string
     */
    const DEV_MODE_ATTRIBUTE = 'data-ampdevmode';

    /**
     * Check whether the provided document is in dev mode.
     *
     * @param DOMDocument $document Document for which to check whether dev mode is active.
     * @return bool Whether the document is in dev mode.
     */
    public static function isActiveForDocument(DOMDocument $document)
    {
        if (! $document instanceof Document) {
            $document = Document::fromNode($document);
        }

        return $document->ampDevModeActive;
    }

    /**
     * Check whether a node is exempt from validation during dev mode.
     *
     * @param DOMNode $node Node to check.
     * @return bool Whether the node should be exempt during dev mode.
     */
    public static function hasExemptionForNode(DOMNode $node)
    {
        if (! $node instanceof DOMElement || ! self::isActiveForDocument( $node->ownerDocument )) {
            return false;
        }

        return $node->hasAttribute(self::DEV_MODE_ATTRIBUTE);
    }

    /**
     * Check whether a certain node should be exempt from validation.
     *
     * @param DOMNode $node Node to check.
     * @return bool Whether the node should be exempt from validation.
     */
    public static function isExemptFromValidation(DOMNode $node)
    {
        $document = $node instanceof Document ? $node : $node->ownerDocument;

        if (! $document) {
            return false;
        }

        return self::isActiveForDocument($document) && self::hasExemptionForNode($node);
    }
}
