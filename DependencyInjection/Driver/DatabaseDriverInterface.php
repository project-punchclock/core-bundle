<?php
namespace Volleyball\Bundle\UtilityBundle\DependencyInjection\Driver;

interface DatabaseDriverInterface
{
    public function load(array $classes);

    public function getSupportedDriver();
}
