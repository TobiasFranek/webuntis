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

namespace Webuntis;

use Doctrine\Common\Annotations\AnnotationRegistry;
use JsonRPC\Client;
use JsonRPC\HttpClient;
use Webuntis\Models\AbstractModel;
use Webuntis\Query\Query;
use Webuntis\Repositories\Repository;

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
    private $session;

    /**
     * @var string
     */
    const PATH_SCHEME = 'https://{server}.webuntis.com/WebUntis/jsonrpc.do?school=';

    /**
     * @var array
     */
    private $user = [];

    /**
     * Webuntis constructor.
     * @param array $config
     */
    public function __construct(array $config) {
        $this->path = str_replace('{server}', $config['server'], static::PATH_SCHEME) . $config['school'];

        $this->user['username'] = $config['username'];
        $this->user['password'] = $config['password'];


        $cache = Repository::getCache();
        $generalConfig = WebuntisFactory::getConfig();
        if ($cache && $cache->contains($config['username']) && (!isset($generalConfig['only_admin']) || $generalConfig['only_admin'] == false)) {
            $data = $cache->fetch($config['username']);
            $this->currentUserId = -1;
            if(isset($data['userId'])){
                $this->currentUserId = $cache->fetch($config['username'])['userId'];
            }
            $this->currentUserType = 0;
            if(isset($data['userType'])) {
                $this->currentUserType = $cache->fetch($config['username'])['userType'];
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
            $this->client = new Client($this->path, false, $httpClient);
        }else {
            $this->client = new Client($this->path);
            $this->authenticate($this->user['username'], $this->user['password']);
        }
    }

    /**
     * authenticates the given user
     * @param $username
     * @param $password
     * @return mixed
     */
    public function authenticate($username, $password) {
        $result = $this->client->execute('authenticate', [$username, $password, rand(1, 4000)]);

        $this->currentUserId = $result['personId'];
        $this->currentUserType = $result['personType'];

        $cache = Repository::getCache();
        if ($cache) {
            $cache->save($username, [
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
     * return the User thats is currently logged in with this instance
     * @return AbstractModel
     */
    public function getCurrentUser() {
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
    public function getCurrentUserType() {
        return $this->currentUserType;
    }

    /**
     * logs the user that is currently logged in in this instance out
     */
    public function logout() {
        $this->client->execute('logout', []);
    }

    /**
     * returns the path
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * sets the path
     * @param string $path
     * @return Webuntis $this
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * returns the client
     * @return Client
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * sets the client
     * @param Client $client
     * @return Webuntis $this
     */
    public function setClient(Client $client) {
        $this->client = $client;

        return $this;
    }

    /**
     * @return string
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * @param string $session
     */
    public function setSession($session) {
        $this->session = $session;
    }
}