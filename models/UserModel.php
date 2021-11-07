<?php

use app\models\DatabaseModel;

namespace app\models;


abstract class UserModel extends DatabaseModel
{
    abstract public function getDisplayName(): string;
}


