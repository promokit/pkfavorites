<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit94c24a653f8e560ad8efa08977524426
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Promokit\\Module\\Pkfavorites\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Promokit\\Module\\Pkfavorites\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Pkfavorites' => __DIR__ . '/../..' . '/pkfavorites.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit94c24a653f8e560ad8efa08977524426::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit94c24a653f8e560ad8efa08977524426::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit94c24a653f8e560ad8efa08977524426::$classMap;

        }, null, ClassLoader::class);
    }
}
