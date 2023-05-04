<?php

namespace Entities;

use DateTime;
use Exception;

class Week
{
    /** @var DateTime|mixed|null $from */
    public ?DateTime $from;

    /** @var DateTime|mixed|null $to */
    public ?DateTime $to;

    /** @var bool $changed */
    public bool $changed;

    /**
     * @param $from
     * @param $to
     */
    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->changed = false;
    }

    /**
     * Get start of the week date
     * @param DateTime $date
     * @return DateTime
     * @throws Exception
     */
    public static function getFrom(DateTime $date): DateTime
    {
        $newDate = new DateTime($date->format('Y-m-d'));
        $newDate->modify('monday this week')->setTime(0, 0);
        return $newDate;
    }

    /**
     * Get end of the week date
     * @param DateTime $date
     * @return DateTime
     * @throws Exception
     */
    public static function getTo(DateTime $date): DateTime
    {
        $newDate = new DateTime($date->format('Y-m-d'));
        $newDate->modify('sunday this week')->setTime(0, 0);
        return $newDate;
    }
}
