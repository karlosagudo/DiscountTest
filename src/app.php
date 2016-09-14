<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Symfony\Component\Finder\Finder;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

$dataFolder = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data';

$finder = new Finder();
$finder->files()->in($dataFolder)->name('*.json');

// We load the data of customers and products
$data = [];
foreach ($finder as $file) {
    $fileNameNoExtension = $file->getBasename('.json'); //http://php.net/manual/en/splfileinfo.getbasename.php
    $dataToImport = file_get_contents($file->getRealPath());
    $data[$fileNameNoExtension] = json_decode($dataToImport, true);
}
$app['data'] = $data;

// We load dinamically the Discounts
$finder = Finder::create();
$discountsFolder = __DIR__.DIRECTORY_SEPARATOR.'Discounts';
$finder->files()->in($discountsFolder)->followLinks(true)->name('*.php');

$discounts = [];
foreach ($finder as $file) {
    $fileNameNoExtension = $file->getBasename('.php');
    $relativePath = $file->getRelativePath();
    if ($relativePath) {
        $discountClass = 'Discounts\\'.$relativePath.'\\'.$fileNameNoExtension; //PSR-0
        $interfacesImplemented = class_implements($discountClass);
        if (!isset($interfacesImplemented['Discounts\\DiscountInterface'])) {
            continue;
        }
        $order = call_user_func([$discountClass, 'getInitializeOrder']);
        if (isset($discounts[$order])) {
            $app->abort('500', "There is a class with order: $order already declared");
        }
        $post = stristr($relativePath, 'post') === false ? false : true;
        $discounts[$order] = [
            'object' => new $discountClass(),
            'post' => true,
        ];
    }
}

sort($discounts);

$app['discounts'] = $discounts;

return $app;
