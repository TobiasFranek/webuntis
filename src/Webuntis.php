<?php
declare(strict_types=1);

namespace Webuntis;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Webuntis\Models\AbstractModel;
use Webuntis\Query\Query;
use Webuntis\Repositories\Repository;
use Webuntis\Security\WebuntisSecurityManager;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Exceptions\ModelException;
use Webuntis\Models\Account;

/**
 * Webuntis is the main instance which stores the client
 * it also logs the user in and out
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
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

        if (isset($config['path_scheme'])) {
            $pathScheme = $config['path_scheme'];
        }
        $this->path = str_replace(['{server}', '{school}'], [$config['server'], $config['school']], $pathScheme);

        $managerClass = static::DEFAULT_SECURITY_MANAGER;

        if (isset(WebuntisConfiguration::getConfig()['security_manager'])) {
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
     * @throws ModelException
     */
    public function getCurrentUser() : object 
    {
        $query = new Query();
        if ($this->currentUserType == 5) {
            return $query->get('Students')->findBy(['id' => $this->currentUserId])[0];
        } else if ($this->currentUserType == 2) {
            return $query->get('Teachers')->findBy(['id' => $this->currentUserId])[0];
        } else {
            return new Account($this->currentUserId, $this->currentUserType);
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