<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    // For Laravel
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->withSets([
        // SetList::DEAD_CODE,
        // SetList::CODE_QUALITY,
        // SetList::CODING_STYLE,
        // SetList::NAMING,
        // SetList::PRIVATIZATION,
        // SetList::TYPE_DECLARATION,
        // SetList::RECTOR_PRESET,
        /*
        The sets in LaravelSetList only contain changes related to a specific version upgrade. 
        For example, the rules in LaravelSetList::LARAVEL_110 apply when upgrading to Laravel 11.
        */
        LaravelLevelSetList::UP_TO_LARAVEL_60,
        
        /*
        Additional Sets, to improve different aspects of your code.
        */
        // LaravelSetList::LARAVEL_CODE_QUALITY,
        // LaravelSetList::LARAVEL_COLLECTION,
        // LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,           
        // LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,        
        // LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        // LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        // LaravelSetList::LARAVEL_IF_HELPERS,
        // LaravelSetList::LARAVEL_STATIC_TO_INJECTION
    ]);