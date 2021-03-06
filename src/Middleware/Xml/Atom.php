<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Middleware\Xml;

use DOMXPath;
use SimplePie\Type\Generator;
use SimplePie\Type\Node;
use stdClass;

/**
 * Support for the Atom 1.0 grammar.
 *
 * @see https://tools.ietf.org/html/rfc4287
 * @see https://www.w3.org/wiki/Atom
 */
class Atom extends AbstractXmlMiddleware implements XmlInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(stdClass $feedRoot, string $namespaceAlias, DOMXPath $xpath): void
    {
        // lang
        $this->addArrayProperty($feedRoot, 'lang');
        $xq = $xpath->query($this->applyNsToQuery('/%s:feed[attribute::xml:lang][1]/@xml:lang', $namespaceAlias));

        $feedRoot->lang[$namespaceAlias] = ($xq->length > 0)
            ? Node::factory((string) $xq->item(0)->value)
            : null;

        // single nodes, scalar values
        foreach (['id', 'rights', 'subtitle', 'summary', 'title'] as $nodeName) {
            $this->addArrayProperty($feedRoot, $nodeName);
            $feedRoot->$nodeName[$namespaceAlias] = $this->getSingle($nodeName, $namespaceAlias, $xpath);
        }

        // time/date stamps
        foreach (['published', 'updated'] as $nodeName) {
            $this->addArrayProperty($feedRoot, $nodeName);
            $feedRoot->$nodeName[$namespaceAlias] = $this->getSingle($nodeName, $namespaceAlias, $xpath);
        }

        // generator
        $this->addArrayProperty($feedRoot, 'generator');
        $xq = $xpath->query($this->generateQuery($namespaceAlias, true, 'feed', 'generator'));

        $feedRoot->generator[$namespaceAlias] = ($xq->length > 0)
            ? new Generator($xq->item(0))
            : null;
    }

    /**
     * Find and read the contents of the feed-level nodes which only have a single value.
     *
     * @param string   $nodeName       The name of the namespaced XML node to read.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Util\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     *
     * @return Node
     */
    protected function getSingle(string $nodeName, string $namespaceAlias, DOMXPath $xpath): Node
    {
        $query = $this->generateQuery($namespaceAlias, true, 'feed', $nodeName);
        $this->getLogger()->debug(\sprintf('%s is running an XPath query:', __CLASS__), [$query]);

        return $this->handleSingleNode(function () use ($xpath, $query) {
            return $xpath->query($query);
        });
    }
}
