<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7744e9be3c3a926bacb7a53dfeaec5dc
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'InspireLabs\\WoocommerceInpost\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'InspireLabs\\WoocommerceInpost\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/InspireLabs/WoocommerceInpost',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7744e9be3c3a926bacb7a53dfeaec5dc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7744e9be3c3a926bacb7a53dfeaec5dc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7744e9be3c3a926bacb7a53dfeaec5dc::$classMap;

        }, null, ClassLoader::class);
    }
}
