<?php

namespace App\Enums;

enum Role: int
{
    case Customer = 1;
    case Admin = 2;
    case Courier = 3;
}