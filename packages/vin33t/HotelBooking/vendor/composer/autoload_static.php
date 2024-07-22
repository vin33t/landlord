<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6cf4cb599dd4ce6c7a595501c6628123
{
    public static $prefixLengthsPsr4 = array (
        'v' => 
        array (
            'vin33t\\HotelBooking\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'vin33t\\HotelBooking\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6cf4cb599dd4ce6c7a595501c6628123::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6cf4cb599dd4ce6c7a595501c6628123::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6cf4cb599dd4ce6c7a595501c6628123::$classMap;

        }, null, ClassLoader::class);
    }
}
