<?php

namespace Tests\Mocks;

class SocialUser implements \Laravel\Socialite\Contracts\User
{
    public string $email;
    public string $firstName;
    public string $lastName;
    public string $nickname;

    public function __construct(
        string $email = 'sociallogin@example.com',
        string $firstName = 'Buzzy',
        string $lastName = 'Boo',
        string $nickname = 'buzzyboo'
    )
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getId()
    {
        return 'a_google_token';
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->firstName;
    }

    public function getAvatar()
    {
        return null;
    }

    public function getRaw()
    {
        return [
            'email_verified' => true,
            'given_name' => $this->firstName,
            'family_name' => $this->lastName,
            'nickname' => $this->nickname,
        ];
    }

    public function getNickname()
    {
        return $this->nickname;
    }
}
