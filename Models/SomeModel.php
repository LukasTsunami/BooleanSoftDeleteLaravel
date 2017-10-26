<?php

namespace App\Models;

use \App\Helpers\BooleanSoftDeletingTrait;

class SomeModel extends Model
{
	use BooleanSoftDeletingTrait;

    const DELETED_AT = 'deleted';

}
