<?php

namespace DElfimov\Session;

use Psr\Container\NotFoundExceptionInterface;
use DElfimov\Session\ContainerException;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
