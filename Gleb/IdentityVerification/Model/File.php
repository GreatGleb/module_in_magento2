<?php

namespace Gleb\IdentityVerification\Model;

use Gleb\IdentityVerification\Api\Data\FileInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class File
 * @package Gleb\IdentityVerification\Model
 */
class File extends AbstractModel implements FileInterface
{
    const BASE_DIR = 'customer';

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getData(FileInterface::ID);
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->setData(FileInterface::ID, $id);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->getData(FileInterface::PATH);
    }

    /**
     * @param string $path
     * @return void
     */
    public function setPath($path)
    {
        $this->setData(FileInterface::PATH, $path);
    }
}
