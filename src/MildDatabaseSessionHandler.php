<?php

namespace Rolandstarke\MildDatabaseSession;

use Illuminate\Session\DatabaseSessionHandler;

/**
 * just like the default DatabaseSessionHandler only that the session does not get updated if there is no change
 */
class MildDatabaseSessionHandler extends DatabaseSessionHandler
{
    /**
     * minimum seconds the session is forced the be updated in db, so that last_activity is accurate
     */
    const MINIMUM_UPDATE_INTERVAL = 120;

    /**
     * session at read time, we can use it to compare if the new payload contains changes
     */
    protected $origSession;

    /**
     * {@inheritdoc}
     *
     * @return string|false
     */
    public function read($sessionId)
    {
        $session = (object) $this->getQuery()->find($sessionId);

        $this->origSession = $session; // <<-- store session from db

        if ($this->expired($session)) {
            $this->exists = true;

            return '';
        }

        if (isset($session->payload)) {
            $this->exists = true;

            return base64_decode($session->payload);
        }

        return '';
    }
    /**
     * Perform an update operation on the session ID. (only if there are changes)
     *
     * @param  string $sessionId
     * @param  array $payload
     * @return int
     */
    protected function performUpdate($sessionId, $payload)
    {
        if (!empty($payload['payload'])) {
            $session = unserialize(base64_decode($payload['payload']));
            if (isset($session['_previous'])) {
                unset($session['_previous']);
                $payload['payload'] = base64_encode(serialize($session));
            }
        }

        foreach ($payload as $key => $value) {
            if (!property_exists($this->origSession, $key)) {
                continue;
            }

            if ($key === 'last_activity') {
                if ($value < $this->origSession->last_activity + self::MINIMUM_UPDATE_INTERVAL) {
                    unset($payload[$key]);
                }
            } else {
                if ($this->origSession->{$key} === $value) {
                    unset($payload[$key]);
                }
            }
        }

        if (empty($payload)) {
            return 0;
        }

        return parent::performUpdate($sessionId, $payload);
    }
}
