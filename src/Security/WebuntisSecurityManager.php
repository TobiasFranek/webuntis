<?php
declare(strict_types=1);

namespace Webuntis\Security;

use Webuntis\Repositories\Repository;
use JsonRPC\Client;
use JsonRPC\HttpClient;
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
     * @var object
     */
    private $cache;

    /**
     * @var string
     */
    private $session;

    /**
     * @var string
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
            $httpClient = new HttpClient($this->path);
            $newDate = $data['tokenCreatedAt']->add(new \DateInterval('PT1200000S'));
            $httpClient->withCookies([
                'JSESSIONID' => $this->session,
                'Path' => '/WebUntis',
                'Version' => '1',
                'Max-Age' => 1209600,
                'Expires' => $newDate->format('D, d-M-Y H:i:s ') . 'GMT'

            ]);
            $client = new $this->clientClass($this->path, false, $httpClient);
        }else {
            $client = new $this->clientClass($this->path);
            $this->authenticate($client);
        }

        return $client;
    }

    /**
     * returns a client of an specific context
     * @return object
     */
    public function getClient() : object 
    {
        if(isset(static::$clients[$this->context])) {
            return static::$clients[$this->context];
        } else {
            static::$clients[$this->context] = $this->createClient();
            return static::$clients[$this->context];
        }
    }

    /**
     * authenticates the user
     * @param object $client 
     * @return array
     */
    private function authenticate(object $client) : array 
    {
        $result = $client->execute('authenticate', [$this->config['username'], $this->config['password'], rand(1, 4000)]);
        $this->currentUserId = intval($result['personId']);
        $this->currentUserType = intval($result['personType']);

        if ($this->cache) {
            $this->cache->save('security.' . $this->context, [
                'session' => $result['sessionId'],
                'userId' => $this->currentUserId,
                'userType' => $this->currentUserType,
                'tokenCreatedAt' => new \DateTime()
            ], 86400);
        }

        $this->session = $result['sessionId'];

        return $result;
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
        static::$clients[$this->context]->execute('logout', []);
        $this->cache->delete('security.' . $this->context);
        unset(static::$clients[$this->context]);
    }
}