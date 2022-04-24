<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd7b0300130a97f4fa71ad9dd6ee02437
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MVC\\' => 4,
        ),
        'C' => 
        array (
            'CONFIG\\' => 7,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MVC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'CONFIG\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd7b0300130a97f4fa71ad9dd6ee02437::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd7b0300130a97f4fa71ad9dd6ee02437::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd7b0300130a97f4fa71ad9dd6ee02437::$classMap;

        }, null, ClassLoader::class);
    }
}
