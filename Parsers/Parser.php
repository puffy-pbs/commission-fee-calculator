<?php

namespace Parsers;

interface Parser
{
    public function parse(array $row);
}
