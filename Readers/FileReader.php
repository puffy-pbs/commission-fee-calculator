<?php

namespace Readers;

use Iterator;

interface FileReader
{
    public function read(): Iterator;
}
