<?php
/**
 * Easy to use translate library for multi-language websites
 *
 * PHP version 5
 *
 * @category Translate
 * @package  Translate
 * @author   Dmitry Elfimov <elfimov@gmail.com>
 * @license  MIT License
 * @link     https://github.com/delfimov/Translate/
 */

namespace DElfimov\Session;

use \Psr\Container\ContainerInterface;
use \SessionHandler;

/**
 * Class Translate
 *
 * @category Translate
 * @package  Translate
 * @author   Dmitry Elfimov <elfimov@gmail.com>
 * @license  MIT License
 * @link     https://github.com/delfimov/Translate/
 */
class Session implements ContainerInterface
{

    public $isStarted = false;

    public function __construct(SessionHandler $handler, $sessionName = 'SID')
    {
        session_name($sessionName);
        session_set_save_handler($handler);
        $this->isStarted = session_start();
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for **this** identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (empty($id) || !is_string($id)) {
            throw new ContainerException('Key must be a string');
        }
        if (!isset($_SESSION[$id])) {
            throw new NotFoundException('Key not found');
        }
        return $_SESSION[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($_SESSION[$id]);
    }

    /**
     * Sets value for specified container identifier
     *
     * @param string $id    Identifier of the entry to look for.
     * @param string $value value
     *
     * @return bool
     */
    public function set($id, $value)
    {
        $_SESSION[$id] = $value;
        return true;
    }

    /**
     * Removes entry with specified identifier
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function remove($id)
    {
        unset($_SESSION[$id]);
        return true;
    }

    /**
     * Get session id.
     *
     * @return string session id.
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Destroy session.
     *
     */
    public function destroy()
    {
        session_destroy();
    }
}
