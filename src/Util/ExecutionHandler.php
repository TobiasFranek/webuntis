<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace Webuntis\Util;

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Repositories\Repository;

/**
 * Class ExecutionHandler
 * @package Webuntis\Util
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class ExecutionHandler {

    private function __construct() {
    }

    /**
     * executes the given command with the right instance, model etc.
     * @param Repository $repository
     * @param array $params
     * @return AbstractModel[]
     */
    public static function execute(Repository $repository, array $params) {
        $model = $repository->getModel();
        $interfaces = class_implements($model);
        $cacheDriver = $repository::getCache();
        if ($cacheDriver && $cacheDriver->contains($model::METHOD) && isset($interfaces[CachableModelInterface::class])) {
            $data = $cacheDriver->fetch($model::METHOD);
        } else {
            $result = $repository->getInstance()->getClient()->execute($model::METHOD, $params);
            $data = $repository->parse($result);

            if ($cacheDriver && isset($interfaces[CachableModelInterface::class])) {
                if ($model::CACHE_LIFE_TIME) {
                    $cacheDriver->save($model::METHOD, $data, $model::CACHE_LIFE_TIME);
                } else {
                    $cacheDriver->save($model::METHOD, $data);
                }
            }
        }
        return $data;
    }
}