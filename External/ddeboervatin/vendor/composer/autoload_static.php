<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6d061ff3783f27cca0b393b09be7852a
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Ddeboer\\Vatin\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ddeboer\\Vatin\\' => 
        array (
            0 => __DIR__ . '/..' . '/ddeboer/vatin/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6d061ff3783f27cca0b393b09be7852a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6d061ff3783f27cca0b393b09be7852a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
