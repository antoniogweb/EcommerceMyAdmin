<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit94305569a9f8eb7128fa7ff248d5901e
{
    public static $files = array (
        '6124b4c8570aa390c21fafd04a26c69f' => __DIR__ . '/..' . '/myclabs/deep-copy/src/DeepCopy/deep_copy.php',
        'c65d09b6820da036953a371c8c73a9b1' => __DIR__ . '/..' . '/facebook/graph-sdk/src/Facebook/polyfills.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'setasign\\Fpdi\\' => 14,
        ),
        'S' => 
        array (
            'Sendpulse\\RestApi\\' => 18,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Mpdf\\' => 5,
        ),
        'F' => 
        array (
            'Facebook\\' => 9,
        ),
        'D' => 
        array (
            'DeepCopy\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'setasign\\Fpdi\\' => 
        array (
            0 => __DIR__ . '/..' . '/setasign/fpdi/src',
        ),
        'Sendpulse\\RestApi\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendpulse/rest-api/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Mpdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/mpdf/mpdf/src',
        ),
        'Facebook\\' => 
        array (
            0 => __DIR__ . '/..' . '/facebook/graph-sdk/src/Facebook',
        ),
        'DeepCopy\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/deep-copy/src/DeepCopy',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit94305569a9f8eb7128fa7ff248d5901e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit94305569a9f8eb7128fa7ff248d5901e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
