<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

use DOMXPath;
use SimplePie\Enum\FeedType;
use SimplePie\Exception\MiddlewareException;
use Skyzyx\UtilityPack\Types;
use stdClass;

/**
 * Provides an interface for `SimplePie\HandlerStack` to implement.
 */
interface HandlerStackInterface
{
    /**
     * Appends a new middleware class onto the end of the stack.
     *
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     *
     * @throws MiddlewareException
     *
     * @return self
     */
    public function append(
        callable $middleware,
        ?string $name = null,
        ?string $overrideType = null
    ): HandlerStackInterface;

    /**
     * Appends a new middleware closure onto the end of the stack.
     *
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     *
     * @throws MiddlewareException
     *
     * @return self
     */
    public function appendClosure(
        string $overrideType,
        callable $middleware,
        ?string $name = null
    ): HandlerStackInterface;

    /**
     * Prepends a new middleware class onto the beginning of the stack.
     *
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     *
     * @throws MiddlewareException
     *
     * @return self
     */
    public function prepend(
        callable $middleware,
        ?string $name = null,
        ?string $overrideType = null
    ): HandlerStackInterface;

    /**
     * Prepends a new middleware closure onto the beginning of the stack.
     *
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     *
     * @throws MiddlewareException
     *
     * @return self
     */
    public function prependClosure(
        string $overrideType,
        callable $middleware,
        ?string $name = null
    ): HandlerStackInterface;

    /**
     * Invokes the stack of middleware.
     *
     * @param string   $feedType       A valid _single_ feed type from `SimplePie\Enum\FeedType`. Since `FeedType::ALL`
     *                                 represents _multiple_ feed types, an exception will be thrown if it is used.
     * @param stdClass $feedRoot       The root of the feed. This will be written-to when the parsing middleware runs.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Dictionary\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     */
    public function invoke(
        string $feedType,
        stdClass $feedRoot,
        ?string $namespaceAlias,
        DOMXPath $xpath
    ): void;
}
