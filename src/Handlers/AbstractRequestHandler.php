<?php
/**
 * Part of the evias/nem-php package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    evias/nem-php
 * @version    1.0.0
 * @author     Grégory Saive <greg@evias.be>
 * @license    MIT License
 * @copyright  (c) 2017-2018, Grégory Saive <greg@evias.be>
 * @link       http://github.com/evias/nem-php
 */
namespace NEM\Handlers;

use GuzzleHttp\Client;
use NEM\Contracts\RequestHandler;
use NEM\Traits\Connectable;

/**
 * This is the AbstractRequestHandler abstract class
 *
 * This class should be extended by RequestHandler
 * specialization classes.
 *
 * @author Grégory Saive <greg@evias.be>
 */
abstract class AbstractRequestHandler
    implements RequestHandler
{
    use Connectable;

    /**
     * This method makes sure mandatory headers are
     * added in case they are not present.
     *
     * @param  array  $headers [description]
     * @return [type]          [description]
     */
    protected function normalizeHeaders(array $headers)
    {
        if (empty($headers["User-Agent"]))
            $headers["User-Agent"] = "evias NEM Blockchain Wrapper";

        if (empty($headers["Accept"]))
            $headers["Accept"] = "application/json";

        if (empty($headers["Content-Type"]))
            $headers["Content-Type"] = "application/json";

        return $headers;
    }

    /**
     * This method triggers a GET request to the given
     * Base URL at the URI `/endpoint` using the GuzzleHttp
     * client.
     *
     * @see  \NEM\Contracts\RequestHandler
     * @param  string $uri
     * @param  string $bodyJSON
     * @param  array  $options
     * @param  boolean  $usePromises
     * @return [type]          [description]
     */
    abstract public function status(array $options = [], $usePromises = false);

    /**
     * This method triggers a GET request to the given
     * URI using the GuzzleHttp client.
     *
     * @see  \NEM\Contracts\RequestHandler
     * @param  string $uri
     * @param  string $bodyJSON
     * @param  array  $options
     * @param  boolean  $usePromises
     * @return [type]          [description]
     */
    abstract public function get($uri, $bodyJSON, array $options = [], $usePromises = false);

    /**
     * This method triggers a POST request to the given
     * URI using the GuzzleHttp client.
     *
     * @see  \NEM\Contracts\RequestHandler
     * @param  string $uri
     * @param  string $bodyJSON
     * @param  array  $options
     * @param  boolean  $usePromises
     * @return [type]          [description]
     */
    abstract public function post($uri, $bodyJSON, array $options = [], $usePromises = false);
}
