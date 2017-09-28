<?php
/**
 * Created by PhpStorm.
 * User: nicu
 * Date: 26/09/2017
 * Time: 13:08
 */

namespace Api\Domain\Repository;

class CalendarRepository extends BaseRepository
{
    protected $table = 'calendar';

    public function getAllChronologically()
    {
        return $this->executeQuery('SELECT * FROM calendar ORDER BY from_date ASC');
    }
}