<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
class PosHelperImageUploader extends HelperImageUploader
{
    /**
     * Resize file or keep the original sizes
     * @var boolean
     */
    public $resize = true;
    /**
     * @var float
     */
    protected $resized_width;

    /**
     * @param float $width
     */
    public function setResizedWidth($width)
    {
        $this->resized_width = $width;
    }

    /**
     * Resize image.
     *
     * @param string $file_path
     * @param string $file_name
     *
     * @return bool
     */
    public function resize($file_path, $file_name)
    {
        $resize_file_sizes = array();
        if ($this->resize) {
            $resize_file_sizes = PosFile::getResizedFileSizes($this->resized_width, $file_path);
        } else {
            $image_sizes = getimagesize($file_path);
            $resize_file_sizes['width'] = $image_sizes[0];
            $resize_file_sizes['height'] = $image_sizes[1];
        }
        return @ImageManager::resize($file_path, $file_name, $resize_file_sizes['width'], $resize_file_sizes['height']);
    }
}
