<?php

namespace Gleb\IdentityVerification\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface FileInterface
 * @package Narfstudios\Connectch\Api\Data
 */
interface FileInterface
{
    const ID = 'id';
    const PATH = 'path';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getPath();

    /**
     * @param string $path
     * @return void
     */
    public function setPath($path);
}
