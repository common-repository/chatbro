<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc8c0c3e3392542ddd99c3719dc9984b8
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Chatbroapp\\Common\\' => 18,
            'Chatbroapp\\Backends\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Chatbroapp\\Common\\' => 
        array (
            0 => __DIR__ . '/../..' . '/common',
        ),
        'Chatbroapp\\Backends\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backends',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc8c0c3e3392542ddd99c3719dc9984b8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc8c0c3e3392542ddd99c3719dc9984b8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc8c0c3e3392542ddd99c3719dc9984b8::$classMap;

        }, null, ClassLoader::class);
    }
}