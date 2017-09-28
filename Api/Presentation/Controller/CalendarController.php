<?php
/**
 * Created by PhpStorm.
 * User: nicu
 * Date: 26/09/2017
 * Time: 12:42
 */

namespace Api\Presentation\Controller;

use Api\Domain\Model\Calendar;
use Api\Domain\Model\User;
use Api\Domain\Repository\CalendarRepository;
use Api\Domain\Repository\UserRepository;

class CalendarController
{
    public function get($id)
    {
        $calendarRepo = new CalendarRepository();

        try {
            $calendar = $calendarRepo->get($id);
        } catch (\Exception $e) {
            return jsonResponse($e->getCode(), $e->getMessage(), ['id' => $id]);
        }

        return jsonResponse(200, 'ok', $calendar->toArray());
    }

    public function all()
    {
        $calendar = new CalendarRepository();

        return jsonResponse(200, 'ok', $calendar->getAllChronologically());
    }

    public function add($request)
    {
        $fromDate = new \DateTime($request['from_date']);
        $toDate = new \DateTime($request['to_date']);

        $calendar = new Calendar();
        $calendar->setDescription($request['description']);
        $calendar->setFromDate($fromDate->format('Y-m-d H:i:s'));
        $calendar->setToDate($toDate->format('Y-m-d H:i:s'));
        $calendar->setLocation($request['location']);
        $calendar->setComment($request['comment']);

        $calendarRepo = new CalendarRepository();
        $calendarRepo->add($calendar);

        return jsonResponse(200, 'New event was saved', $calendar->toArray());
    }

    public function update($id, $request)
    {
        $calendarRepo = new CalendarRepository();

        $fromDate = new \DateTime($request['from_date']);
        $toDate = new \DateTime($request['to_date']);

        try {
            $calendar = $calendarRepo->get($id);
        } catch (\Exception $e) {
            return jsonResponse($e->getCode(), $e->getMessage(), ['id' => $id]);
        }

        $calendar->setFromDate($fromDate->format('Y-m-d H:i:s'));
        $calendar->setToDate($toDate->format('Y-m-d H:i:s'));
        $calendarRepo->update($calendar);

        return jsonResponse(200, 'Event was updated', $calendar->toArray());
    }

    public function delete($id)
    {
        $calendarRepo = new CalendarRepository();
        try {
            $calendarRepo->delete($id);
        } catch (\Exception $e) {
            return jsonResponse($e->getCode(), $e->getMessage(), ['id' => $id]);
        }

        return jsonResponse(200, 'Event was deleted', ['id' => $id]);
    }
}