<?php

namespace Ekreative\RedmineLoginBundle;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EkreativeRedmineLoginBundle extends Bundle
{
    public function __construct()
    {
        // Prevent crashing when nelmio/api-doc-bundle is not installed
        AnnotationReader::addGlobalIgnoredName('ApiDoc');
    }
}
