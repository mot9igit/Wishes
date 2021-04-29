<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/Wishes/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/wishes')) {
            $cache->deleteTree(
                $dev . 'assets/components/wishes/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/wishes/', $dev . 'assets/components/wishes');
        }
        if (!is_link($dev . 'core/components/wishes')) {
            $cache->deleteTree(
                $dev . 'core/components/wishes/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/wishes/', $dev . 'core/components/wishes');
        }
    }
}

return true;