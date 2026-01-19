<?php

namespace App\Enums\Auth;

use App\Enums\Traits\EnumToArray;

enum PermissionType: string
{
    use EnumToArray;

    case USER_ACCESS = 'user_access';
    case USER_MANAGE = 'user_manage';

    case GENRE_ACCESS = 'genre_access';
    case GENRE_MANAGE = 'genre_manage';

    case BOARDGAME_ACCESS = 'boardgame_access';
    case BOARDGAME_MANAGE = 'boardgame_manage';
}
