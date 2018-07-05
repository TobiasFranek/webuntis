<?php
declare(strict_types=1);

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

namespace Webuntis;

use Doctrine\Common\Annotations\AnnotationRegistry;
use JsonRPC\Client;
use JsonRPC\HttpClient;
use Webuntis\Models\AbstractModel;
use Webuntis\Query\Query;
use Webuntis\Repositories\Repository;
use Webuntis\Security\WebuntisSecurityManager;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * Class Webuntis
 * @package Webuntis
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Webuntis {

    /**
     * @var string
     */
    private $path;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var int
     */
    private $currentUserId;

    /**
     * @var int
     */
    private $currentUserType;

    /**
     * @var string
     */
    private $context;

    /**
     * @var string
     */
    const DEFAULT_SECURITY_MANAGER = WebuntisSecurityManager::class;

    /**
     * @var string
     */
    const DEFAULT_PATH_SCHEME = 'https://{server}.webuntis.com/WebUntis/jsonrpc.do?school={school}';

    /**
     * Webuntis constructor.
     * @param array $config
     * @param string $context
     */
    public function __construct(array $config, string $context) {

        $pathScheme = static::DEFAULT_PATH_SCHEME;
        $this->context = $context;

        if(isset($config['path_scheme'])) {
            $pathScheme = $config['path_scheme'];
        }
        $this->path = str_replace(['{server}', '{school}'], [$config['server'], $config['school']], $pathScheme);

        $managerClass = static::DEFAULT_SECURITY_MANAGER;

        if(isset(WebuntisConfiguration::getConfig()['security_manager'])) {
            $managerClass = WebuntisConfiguration::getConfig()['security_manager'];
        } 
        $manager = new $managerClass($this->path, $config, $context);

        $this->client = $manager->getClient();
        $this->currentUserId = $manager->getCurrentUserId();
        $this->currentUserType = $manager->getCurrentUserType();
       
    }

    /**
     * return the User thats is currently logged in with this instance
     * @return AbstractModel
     */
    public function getCurrentUser() : object 
    {
        $query = new Query();
        if($this->currentUserType == 5) {
            return $query->get('Students')->findBy(['id' => $this->currentUserId])[0];
        }else if($this->currentUserType == 2) {
            return $query->get('Teachers')->findBy(['id' => $this->currentUserId])[0];
        }
    }

    /**
     * return the current user type (5 = student, 2 = teacher)
     * @return int
     */
    public function getCurrentUserType() : int 
    {
        return $this->currentUserType;
    }

    /**
     * returns the path
     * @return string
     */
    public function getPath() : string 
    {
        return $this->path;
    }

    /**
     * sets the path
     * @param string $path
     * @return Webuntis
     */
    public function setPath(string $path) : self 
    {
        $this->path = $path;

        return $this;
    }

    /**
     * returns the client
     * @return Client
     */
    public function getClient() : object 
    {
        return $this->client;
    }

    /**
     * sets the client
     * @param Client $client
     * @return Webuntis
     */
    public function setClient(Client $client) : self 
    {
        $this->client = $client;

        return $this;
    }

    /**
     * return the context of the instance
     * @return string
     */
    public function getContext() : string 
    {
        return $this->context;
    }   
}