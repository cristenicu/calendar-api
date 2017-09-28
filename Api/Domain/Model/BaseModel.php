<?php
/**
 * Created by PhpStorm.
 * User: nicu
 * Date: 26/09/2017
 * Time: 15:14
 */

namespace Api\Domain\Model;


interface BaseModel
{
    public function getId();
    public function setId($id);
}