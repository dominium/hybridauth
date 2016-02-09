<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2015 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace Hybridauth\Provider;

use Hybridauth\Adapter\OAuth1;
use Hybridauth\Exception\UnexpectedValueException;
use Hybridauth\Data;
use Hybridauth\User;

class Px500 extends OAuth1
{
    /**
    * {@inheritdoc}
    */
    protected $apiBaseUrl = 'https://api.500px.com/v1/';

    /**
    * {@inheritdoc}
    */
    protected $authorizeUrl = 'https://api.500px.com/v1/oauth/authorize';

    /**
    * {@inheritdoc}
    */
    protected $requestTokenUrl = 'https://api.500px.com/v1/oauth/request_token';

    /**
    * {@inheritdoc}
    */
    protected $accessTokenUrl = 'https://api.500px.com/v1/oauth/access_token';

    /**
    * {@inheritdoc}
    */
    public function getUserProfile()
    {
        $response = $this->apiRequest('users');

        $data = new Data\Collection($response);

        if (! $data->exists('id')) {
            throw new UnexpectedValueException('Provider API returned an unexpected response.');
        }

        $userProfile = new User\Profile();

        $data = $data->filter('user');

        $userProfile->identifier    = $data->get('id');
        $userProfile->displayName   = $data->get('username');
        $userProfile->description   = $data->get('about');
        $userProfile->firstName     = $data->get('firstname');
        $userProfile->lastName      = $data->get('lastname');
        $userProfile->photoURL      = $data->get('userpic_url');
        $userProfile->city          = $data->get('city');
        $userProfile->region        = $data->get('state');
        $userProfile->country       = $data->get('country');

        $userProfile->profileURL    = $data->exists('domain') ? ('http://' . $data->get('domain')) : '';

        return $userProfile;
    }
}
