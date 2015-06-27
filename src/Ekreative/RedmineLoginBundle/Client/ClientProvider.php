<?php
/**
 * Created by mcfedr on 27/06/15 12:42
 */

namespace Ekreative\RedmineLoginBundle\Client;

use Ekreative\RedmineLoginBundle\Security\RedmineUser;
use GuzzleHttp\Client;

class ClientProvider
{
    public function __construct($redmine)
    {
        $this->redmine = $redmine;
    }

    public function get(RedmineUser $user)
    {
        return new Client([
            'base_uri' => $this->redmine,
            'headers' => [
                'X-Redmine-API-Key' => $user->getApiKey()
            ]
        ]);
    }
}
