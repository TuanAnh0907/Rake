<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite02c14fa6c4a5bbaae03d79d9dcb16bc
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TuanAnh\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TuanAnh\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInite02c14fa6c4a5bbaae03d79d9dcb16bc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite02c14fa6c4a5bbaae03d79d9dcb16bc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite02c14fa6c4a5bbaae03d79d9dcb16bc::$classMap;

        }, null, ClassLoader::class);
    }
}
