<?php

namespace Builders;

use Entities\User;

final class UserBuilder implements Builder
{
    /** @var User $user */
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Set id
     * @param int $id
     * @return $this
     */
    public function setId(int $id): UserBuilder
    {
        $this->user->id = $id;
        return $this;
    }

    /**
     * Set type
     * @param string $type
     * @return $this
     */
    public function setType(string $type): UserBuilder
    {
        $this->user->type = $type;
        return $this;
    }

    /**
     * Build
     * @return User
     */
    public function build(): User
    {
        return $this->user;
    }
}
