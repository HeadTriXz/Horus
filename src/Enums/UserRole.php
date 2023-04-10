<?php

namespace Horus\Enums;

enum UserRole: int
{
    case STUDENT = 0;
    case TEACHER = 1;
    case ADMIN = 2;
}
