<?php
declare(strict_types=1);

namespace Webuntis\Client;

use Datto\JsonRpc\Http\Client as JsonRpcClient;
use Webuntis\Exceptions\HttpException;

/**
 * Client for json rpc
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Client extends JsonRpcClient {

    /**
     * the logic which is used to create an Memcache instance
     * @param string $method
     * @param array $arguments
     * @return self
     * @throws HttpException
     */
    public function call(string $method, array $arguments = null) : array
    {
        $this->query(1, $method, $arguments);
        $result = $this->send()[0];
        if ($result->isError()) {
            $error = $result->getError();
            throw new HttpException($error->getMessage(), $error->getCode());
        } else {
            if (gettype($result->getResult()) == 'array') {
                return $result->getResult();
            } else {
                return [$result->getResult()];
            }
        }
    }
}