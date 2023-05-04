<?php

namespace Entities;

class UserInfo
{
    /** @var User $user */
    public User $user;

    /** @var Week $week */
    public Week $week;

    /**
     * @param User $user
     * @param Week $week
     */
    public function __construct(User $user, Week $week)
    {
        $this->user = $user;
        $this->week = $week;
    }
}
