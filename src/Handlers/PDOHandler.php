<?php

namespace DElfimov\Session\Handlers;

use \SessionHandler;
use \PDO;

class PDOHandler extends SessionHandler
{

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $dbTable;

    public function __construct(PDO $pdo, $dbTable = 'sessions')
    {
        $this->pdo = $pdo;
        $this->dbTable = $dbTable;
    }


    /**
     * Initialize session
     * @link http://php.net/manual/en/sessionhandler.open.php
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $session_name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open($save_path, $session_name)
    {
        return true;
    }

    /**
     * Close the session
     * @link http://php.net/manual/en/sessionhandler.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close()
    {
        return true;
    }

    /**
     * Return a new session ID
     * @link http://php.net/manual/en/sessionhandler.create-sid.php
     * @return string <p>A session ID valid for the default session handler.</p>
     * @since 5.5.1
     */
    // @codingStandardsIgnoreStart
    public function create_sid()
    {
    // @codingStandardsIgnoreEnd
        if (function_exists('openssl_random_pseudo_bytes')) {
            return md5(bin2hex(openssl_random_pseudo_bytes(16)));
        } else {
            return md5(uniqid(rand(), true));
        }
    }

    /**
     * Destroy a session
     * @link http://php.net/manual/en/sessionhandler.destroy.php
     * @param string $sessionId The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy($sessionId)
    {
        return $this->pdo->prepare('DELETE FROM `'.$this->dbTable.'` WHERE id = ?')
            ->execute([$sessionId]);
    }

    /**
     * Cleanup old sessions
     * @link http://php.net/manual/en/sessionhandler.gc.php
     * @param int $lifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc($lifetime)
    {
        return $this->pdo->prepare('DELETE FROM `'.$this->dbTable.'` WHERE last_activity < ?')
            ->execute([time() - $lifetime]);
    }


    /**
     * Read session data
     * @link http://php.net/manual/en/sessionhandler.read.php
     * @param string $sessionId The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read($sessionId)
    {
        $result = $this->pdo->prepare('SELECT `data` FROM `'.$this->dbTable.'` WHERE id = ? LIMIT 1');
        $result->execute([$sessionId]);
        $sessionData = $result->fetchColumn();
        return empty($sessionData) ? '' : $sessionData;
    }

    /**
     * Write session data
     * @link http://php.net/manual/en/sessionhandler.write.php
     * @param string $sessionId The session id.
     * @param string $data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function write($sessionId, $data)
    {
        $query = $this->pdo->prepare('UPDATE `'.$this->dbTable.'` SET `data` = ?, last_activity = ? WHERE id = ?');
        $return = $query->execute([$data, time(), $sessionId]);
        // No session to update? Create a new one
        if ($query->rowCount() == 0) {
            $return = $this->pdo
                ->prepare('INSERT INTO `'.$this->dbTable.'` (id, `data`, last_activity) VALUES (?, ?, ?)')
                ->execute([$sessionId, $data, time()]);
        }
        return $return;
    }

    /**
     * Validate session id
     * @param string $session_id The session id
     * @return bool <p>
     * Note this value is returned internally to PHP for processing.
     * </p>
     */
    public function validateId($session_id)
    {
        return is_string($session_id) && strlen($session_id) == 32;
    }

    /**
     * Update timestamp of a session
     * @param string $sessionId The session id
     * @param string $data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool
     */
    public function updateTimestamp($sessionId, $data)
    {
        $query = $this->pdo->prepare('UPDATE `'.$this->dbTable.'` SET `data` = ?, last_activity = ? WHERE id = ?');
        return $query->execute([
            $data,
            time(),
            $sessionId
        ]);
    }
}
