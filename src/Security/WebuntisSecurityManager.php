<?php
declare(strict_types=1);

namespace Webuntis\Security;

use Webuntis\Repositories\Repository;
use Webuntis\Client\Client;
use Webuntis\Security\Interfaces\SecurityManagerInterface;
use Webuntis\CacheBuilder\CacheBuilder;

/**
 * The Security Manager serves the right client with the right credentials to the right instance
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class WebuntisSecurityManager implements SecurityManagerInterface {

    /**
     * @var int
     */
    private $currentUserId;

    /**
     * @var int
     */
    private $currentUserType;

    /**
     * @var array
     */
    private static $clients; 

    /**
     * @var string
     */
    private $path;

    /** 
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $context;
    
    /**
     * @var object|bool
     */
    private $cache;

    /**
     * @var string
     */
    private $session;

    /**
     * @var string|object
     */
    private $clientClass;
    
    /**
     * WebuntisSecurityManager constructor.
     * @param string $path
     * @param array $config
     * @param string $context
     */
    public function __construct(string $path, array $config, string $context, $clientClass = null) 
    {
        if($clientClass) {
            $this->clientClass = $clientClass;
        } else {
            $this->clientClass = Client::class;
        }

        $this->config = $config;
        $this->path = $path;
        $this->context = $context;

        $cacheBuilder = new CacheBuilder();

        $this->cache = $cacheBuilder->create();
    }

    /**
     * creates a new Client instance
     * @return object
     */
    private function createClient() : object
    {
        $client;
        if ($this->cache && $this->cache->contains('security.' . $this->context)) {
            $data = $this->cache->fetch('security.' . $this->context);
            $this->currentUserId = -1;
            if(isset($data['userId'])){
                $this->currentUserId = $data['userId'];
            }
            $this->currentUserType = 0;
            if(isset($data['userType'])) {
                $this->currentUserType = $data['userType'];
            }
            $this->session = $data['session'];
            $newDate = $data['tokenCreatedAt']->add(new \DateInterval('PT1200000S'));
            
            $cookie = 'JSESSIONID=' . $this->session . '; Path=/WebUntis; Version=1; Max-Age=1209600; Expires=' . $newDate->format('D, d-M-Y H:i:s ') . 'GMT;';

            if(gettype($this->clientClass) == 'object') {
                $client = $this->clientClass;
            } else if (gettype($this->clientClass) == 'string') {
                $client = new $this->clientClass($this->path);
                $client->setHeader('Cookie', $cookie);
            }
        }else {
            if(gettype($this->clientClass) == 'object') {
                $client = $this->clientClass;
            } else if (gettype($this->clientClass) == 'string') {
                $client = new $this->clientClass($this->path);
            }
            $client = $this->authenticate($client);
        }

        return $client;
    }

    /**
     * returns a client of an specific context
     * @return object
     */
    public function getClient() : object 
    {
        if(isset(self::$clients[$this->context])) {
            return self::$clients[$this->context];
        } else {
            self::$clients[$this->context] = $this->createClient();
            return self::$clients[$this->context];
        }
    }

    /**
     * authenticates the user
     * @param object $client 
     * @return object
     */
    private function authenticate(object $client) : object 
    {
        $result = $client->call('authenticate', [$this->config['username'], $this->config['password'], rand(1, 4000)]);
        $this->currentUserId = intval($result['personId']);
        $this->currentUserType = intval($result['personType']);
        $createdAt = new \DateTime();
        if ($this->cache) {
            $this->cache->save('security.' . $this->context, [
                'session' => $result['sessionId'],
                'userId' => $this->currentUserId,
                'userType' => $this->currentUserType,
                'tokenCreatedAt' => $createdAt
            ], 86400);
        }
        $newDate = $createdAt->add(new \DateInterval('PT1200000S'));

        $this->session = $result['sessionId'];

        $cookie = 'JSESSIONID=' . $this->session . '; Path=/WebUntis; Version=1; Max-Age=1209600; Expires=' . $newDate->format('D, d-M-Y H:i:s ') . 'GMT;';
            
        $client->setHeader('Cookie', $cookie);
        return $client;
    }

    /**
     * returns the id of the current user
     * @return int
     */
    public function getCurrentUserId() : int 
    {
        return $this->currentUserId;
    }

    /**
     * return the type of the current user
     * @return int
     */
    public function getCurrentUserType() : int 
    {
        return $this->currentUserType;
    }

    /**
     * terminates the current user session
     */
    public function logout() : void 
    {
        self::$clients[$this->context]->call('logout', []);
        $this->cache->delete('security.' . $this->context);
        unset(self::$clients[$this->context]);
    }
}