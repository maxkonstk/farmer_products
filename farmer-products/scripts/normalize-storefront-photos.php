<?php

declare(strict_types=1);

if (! extension_loaded('imagick')) {
    fwrite(STDERR, "The Imagick PHP extension is required.\n");
    exit(1);
}

$root = dirname(__DIR__);
$cacheDir = $root.'/.cache/storefront-photo-sources';

if (! is_dir($cacheDir) && ! mkdir($cacheDir, 0775, true) && ! is_dir($cacheDir)) {
    fwrite(STDERR, "Cannot create cache directory: {$cacheDir}\n");
    exit(1);
}

$sources = [
    'public/images/hero/farm-market.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/farm-produce-Ftn5tCIVhrCb0VIhhsZkd.jpg',

    'public/images/categories/vegetables.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/fresh-vegetables-on-a-farmers-market-87VBlKWZwc3hr3Hh7MlWR.jpg',
    'public/images/categories/fruits.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/crate-with-fresh-fruit-WGVUHixXHhfHx61MmfS5p.jpg',
    'public/images/categories/berries.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/freshly-picked-berries-in-a-basket-R4MuOPL4ajAFGURiNuovq.jpg',
    'public/images/categories/greens.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/organic-green-vegetables-ZcBzRrWQ_cex59GERw_Kd.jpg',
    'public/images/categories/dairy.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/homemade-ricotta-cheese-o2M6XFO9mktVgAGbUZLx6.jpg',
    'public/images/categories/cheese.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/cheese-with-honey-salty-biscuits-and-grapes-gd2NIyAKtrTbxfS9zw4mM.jpg',
    'public/images/categories/meat.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/meat-vendor-at-farmers-market-zq8yhrEnhyIeSr8sT05r9.jpg',
    'public/images/categories/honey.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/rustic-nuts-and-honey-on-wooden-table-T5EqSRsbAkZTP44k3WJ2Q.jpg',
    'public/images/categories/bakery.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/freshly-baked-artisan-bread-loaves-in-baskets-u0JtmOMZupD5GWXM8rR2S.jpg',
    'public/images/categories/preserves.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/food-preservation-in-jars-eHyRCbyCLr-HgZNUcsvdN.jpg',

    'public/images/products/tomatoes.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/fresh-and-juicy-tomatoes-with-water-droplets-RnwJZQA-9tJvsiwolkgUW.jpg',
    'public/images/products/cucumbers.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/healthy-fresh-vegetables-salad-with-cucumber-i04zu3qIWAfVyegyiSvBB.jpg',
    'public/images/products/carrots.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/organic-root-crops-and-other-vegetables-4xdsM-UorbUaEFi75I6wA.jpg',
    'public/images/products/apples.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/golden-apples--UyesG-jHMCIxmTb4YPbr.jpg',
    'public/images/products/pears.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/green-pears-TjfPgK7l53ZwK1XTl4aja.jpg',
    'public/images/products/plums.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/fresh-bio-plum-xIM4PbmUeAyru-qqsav8v.jpg',
    'public/images/products/strawberries.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/juicy-red-strawberries-close-up-photography-Z2DN86ELcxMWxYdMctfNU.jpg',
    'public/images/products/raspberries.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/raspberries-close-up-Su6LZbqAXyd-xk8CRGWJf.jpg',
    'public/images/products/blueberries.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/delicious-blueberry-jam-with-fresh-berries-fSKlYSEbzKzIOglJG3yUS.jpg',
    'public/images/products/lettuce.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/full-frame-of-fresh-pak-choi-vegetables-0Q4BJlp5biFaw3Bo_TMdQ.jpg',
    'public/images/products/herbs.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/fresh-herbs-on-wooden-cutting-board--tBbfw4GZIVYmzVCaYSt-.jpg',
    'public/images/products/milk.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/milk-with-cinnamon-jXzzf-lMy2JQzzlo4LdOj.jpg',
    'public/images/products/cottage-cheese.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/homemade-ricotta-cheese-o2M6XFO9mktVgAGbUZLx6.jpg',
    'public/images/products/cheese-board.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/cheese-appetizer-LCCPnF-IspYpPFkk7WR4X.jpg',
    'public/images/products/butter.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/bread-with-butter-0ytFzbfc6t5lmXsPN3qXG.jpg',
    'public/images/products/chicken.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/grilled-chicken-with-lemon-rosemary-garnish-LYAh-e6hwwJj1INUzN2fY.jpg',
    'public/images/products/beef.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/juicy-medium-steak-ftR2Q083uSrS9CIzyo24c.jpg',
    'public/images/products/honey-jar.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/bread-with-honey-yy45XjwJE7bPoJzFGy_aV.jpg',
    'public/images/products/honeycomb.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/honey-on-bread-4I860Wm6ajBibLsXHGhoF.jpg',
    'public/images/products/bread.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/fresh-sourdough-bread-on-farmers-market-JW2kulNW_6VlTHaITj_l2.jpg',
    'public/images/products/pastry.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/flaky-croissants-in-a-charming-basket-gJYqeL1uVNsYA3D6Yeoka.jpg',
    'public/images/products/jam.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/homemade-strawberry-jam-in-a-jar-8YTQARWX61LY-q0GlVHpK.jpg',
    'public/images/products/pickles.jpg' => 'https://pub-aaa82e9851064d22b954c3ebbafc9ae6.r2.dev/legacy/masters/jars-with-pickled-vegetables-yDJYNW0BhJWOwOUb3BIM1.jpg',
];

foreach ($sources as $relativePath => $sourceUrl) {
    $outputPath = $root.'/'.$relativePath;
    $sourcePath = downloadSource($sourceUrl, $cacheDir);

    normalizeImage($sourcePath, $outputPath);
    echo "Wrote {$relativePath}\n";
}

function downloadSource(string $url, string $cacheDir): string
{
    $extension = pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION) ?: 'jpg';
    $cachePath = $cacheDir.'/'.sha1($url).'.'.$extension;

    if (is_file($cachePath) && filesize($cachePath) > 0) {
        return $cachePath;
    }

    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: Mozilla/5.0\r\n",
            'timeout' => 30,
        ],
    ]);

    $image = @file_get_contents($url, false, $context);

    if ($image === false || $image === '') {
        throw new RuntimeException("Cannot download source: {$url}");
    }

    file_put_contents($cachePath, $image);

    return $cachePath;
}

function normalizeImage(string $sourcePath, string $outputPath): void
{
    $image = new Imagick($sourcePath);
    $image = $image->coalesceImages()->getImage();
    $image->autoOrient();

    cropToRatio($image, 4 / 3);
    $image->resizeImage(1200, 900, Imagick::FILTER_LANCZOS, 1);

    // Same edit for every image keeps mixed photo sources visually coherent.
    $image->modulateImage(103, 95, 100);
    $image->sigmoidalContrastImage(true, 3, 48);
    applyWarmOverlay($image);

    $image->stripImage();
    $image->setImageFormat('jpeg');
    $image->setImageCompressionQuality(86);
    $image->setInterlaceScheme(Imagick::INTERLACE_JPEG);

    $outputDir = dirname($outputPath);

    if (! is_dir($outputDir) && ! mkdir($outputDir, 0775, true) && ! is_dir($outputDir)) {
        throw new RuntimeException("Cannot create output directory: {$outputDir}");
    }

    $image->writeImage($outputPath);
    $image->clear();
}

function cropToRatio(Imagick $image, float $targetRatio): void
{
    $width = $image->getImageWidth();
    $height = $image->getImageHeight();
    $ratio = $width / $height;

    if ($ratio > $targetRatio) {
        $cropWidth = (int) round($height * $targetRatio);
        $x = (int) floor(($width - $cropWidth) / 2);
        $image->cropImage($cropWidth, $height, $x, 0);
    } else {
        $cropHeight = (int) round($width / $targetRatio);
        $y = (int) floor(($height - $cropHeight) / 2);
        $image->cropImage($width, $cropHeight, 0, $y);
    }

    $image->setImagePage(0, 0, 0, 0);
}

function applyWarmOverlay(Imagick $image): void
{
    $overlay = new Imagick();
    $overlay->newImage(1200, 900, new ImagickPixel('rgba(244, 223, 190, 0.08)'));
    $overlay->setImageFormat('png');

    $image->compositeImage($overlay, Imagick::COMPOSITE_OVER, 0, 0);
    $overlay->clear();
}
