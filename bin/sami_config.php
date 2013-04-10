<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in($dir = __DIR__.'/../src')
;

return new Sami($iterator, array(
    'title'                => 'Albatros API',
    'build_dir'            => __DIR__.'/../web/uploads/api',
    'cache_dir'            => __DIR__.'/../web/cache/sami',
    'default_opened_level' => 2,
));
