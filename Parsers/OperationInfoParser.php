<?php

namespace Parsers;

use Builders\OperationInfoBuilder;
use Builders\UserBuilder;

class OperationInfoParser implements Parser
{
    /**
     * Parse array
     * @param array $row
     * @return OperationInfoParsedData
     */
    public function parse(array $row): OperationInfoParsedData
    {
        // User
        $user = (new UserBuilder())
            ->setId(+$row[1])
            ->setType($row[2])
            ->build();

        // Operation info
        $operationInfo = (new OperationInfoBuilder())
            ->setDate($row[0])
            ->setOperationType($row[3])
            ->setAmount(floatval($row[4]))
            ->setCurrency($row[5])
            ->build();

        // Return
        return new OperationInfoParsedData($user, $operationInfo);
    }

}
