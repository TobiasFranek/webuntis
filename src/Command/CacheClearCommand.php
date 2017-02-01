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

namespace Webuntis\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\Cache\Memcached;


/**
 * Class CacheClearCommand
 * @package Webuntis\Command
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class CacheClearCommand extends Command{
    protected function configure() {
        $this->setName('webuntis:cache:clear')
            ->setDescription('cleares the webuntis cache')
            ->setHelp('This Command clears the webuntis cache')
            ->addArgument('port', InputArgument::OPTIONAL, 'port of the memcached server')
            ->addArgument('host', InputArgument::OPTIONAL, 'host of the memcached server');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $helper = $this->getHelper('question');
        if(!$port = $input->getArgument('port')) {
            $question = new Question('Port [11211]: ', 11211);
            $port = $helper->ask($input, $output, $question);

        }
        if(!$host = $input->getArgument('host')) {
            $question = new Question('Host [localhost]: ', 'localhost');
            $host = $helper->ask($input, $output, $question);
        }

        $cacheDriver = new Memcached();
        if (extension_loaded('memcached')) {
            $memcached = new \Memcached();
            $memcached->addServer($host, $port);
            $cacheDriver->setMemcached($memcached);
            $cacheDriver->deleteAll();
            $output->writeln('<info>Successfully cleared the cache</info>');
        } else {
            $output->writeln('<error>extension memcached not found</error>');
        }
    }
}