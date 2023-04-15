<?php

namespace Horus\Enums;

/**
 * Represents the role of a user.
 */
enum UserRole: int
{
    case STUDENT = 0;
    case TEACHER = 1;
    case ADMIN = 2;
}
