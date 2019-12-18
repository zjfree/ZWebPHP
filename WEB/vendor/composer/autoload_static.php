<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6816c4b5de0d9828dda801779a91172f
{
    public static $files = array (
        'f084d01b0a599f67676cffef638aa95b' => __DIR__ . '/..' . '/smarty/smarty/libs/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'z' => 
        array (
            'zphp\\' => 5,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'zphp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/zphp',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6816c4b5de0d9828dda801779a91172f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6816c4b5de0d9828dda801779a91172f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}