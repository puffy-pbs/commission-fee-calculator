<?php

namespace Parsers;

use Entities\OperationInfo;
use Entities\User;

class OperationInfoParsedData
{
    /** @var User $user */
    public User $user;

    /** @var OperationInfo $operationInfo */
    public OperationInfo $operationInfo;

    /**
     * @param User $user
     * @param OperationInfo $operationInfo
     */
    public function __construct(User $user, OperationInfo $operationInfo)
    {
        $this->user = $user;
        $this->operationInfo = $operationInfo;
    }
}
