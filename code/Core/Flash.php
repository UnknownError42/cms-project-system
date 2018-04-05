<?php
namespace Core;

use Core\Flash\Message;

/**
 * Class Flash
 * @package Core
 
 */
class Flash {

    /**
     * Set custom message
     *
     * @param $type
     * @param $message
     * @return $this
     */
    protected function _setMessage($type, $message) {
        $session = $this->_getSession();
        $collection = array();
        if ($session->has('messages')) {
            $collection = $session->get('messages');
        }
        $collection[] = new Message($type, $message);
        $session->set('messages', $collection);
        return $this;
    }

    /**
     * Set success message
     *
     * @param $message
     * @return Flash
     */
    public function success($message) {
        return $this->_setMessage('success', $message);
    }

    /**
     * Set error message
     *
     * @param $message
     * @return Flash
     */
    public function error($message) {
        return $this->_setMessage('danger', $message);
    }

    /**
     * Set warning message
     *
     * @param $message
     * @return Flash
     */
    public function warning($message) {
        return $this->_setMessage('warning', $message);
    }

    /**
     * Returns all messages
     *
     * @return Message[]
     */
    public function getMessages() {
        $session = $this->_getSession();
        if ($session->has('messages')) {
            $messages = $session->get('messages');
            $session->remove('messages');
            return $messages;
        }
        return array();
    }

    /**
     * Returns session instance
     *
     * @return \Core\Session
     */
    protected function _getSession() {
        return Di::getSession();
    }
}