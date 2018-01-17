<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Working with files on server.
 */
class PosFile
{

    /**
     * @param string $location
     * @param array  $files    List of files to be deleted
     *                         <pre>
     *                         array(
     *                         string, // filename and relative file path, fx: path/to/folder1/folder1/name.php
     *                         string,
     *                         )
     *
     * @return bool
     */
    public static function deleteFiles($location, array $files)
    {
        $success = true;
        if (!empty($files)) {
            $location = self::normalizeDirectory($location);
            foreach ($files as $file) {
                $file_path = $location . $file;
                if (is_file($file_path)) {
                    $success &= unlink($file_path);
                }
            }
        }

        return $success;
    }

    /**
     * @param string $location
     * @param array  $directories List of directories to be deleted
     *                            <pre>
     *                            array(
     *                            string, // filename and relative path, fx: path/to/folder1/folder1
     *                            string,
     *                            )
     *
     * @return bool
     */
    public static function deleteDirectories($location, array $directories)
    {
        $success = true;
        if (!empty($directories)) {
            $location = self::normalizeDirectory($location);
            foreach ($directories as $directory) {
                $directory_path = $location . $directory;
                if (!is_dir($directory_path)) {
                    continue; // Chances are, this directory has been deleted.
                }
                $item = array_diff(scandir($directory_path), array('.', '..'));
                foreach ($item as $file) {
                    $success &= (is_dir("$directory_path/$file")) ? self::deleteDirectories($directory_path, array($file)) : unlink("$directory_path/$file");
                }
                $success &= rmdir($directory_path);
            }
        }

        return $success;
    }

    /**
     * Make sure the directory ends with "/" or "\".
     *
     * @see PrestaShopAutoload::normalizeDirectory()
     *
     * @param string $directory
     *
     * @return string
     */
    protected static function normalizeDirectory($directory)
    {
        return rtrim($directory, '/\\') . DIRECTORY_SEPARATOR;
    }

    /**
     * @param int    $max_width
     * @param string $file_path
     *
     * @return array
     *               <pre>
     *               array(
     *               'width' => int,
     *               'height' => int
     *               )
     *               </pre>
     */
    public static function getResizedFileSizes($max_width, $file_path)
    {
        $image_sizes = getimagesize($file_path);
        $resize_file_sizes = array();
        $real_width = $image_sizes[0];
        $real_height = $image_sizes[1];
        if ($real_width > $max_width) {
            $ratio = $max_width / $real_width;
            $resize_file_sizes['height'] = $ratio * $real_height;
            $resize_file_sizes['width'] = $max_width;
        } else {
            $resize_file_sizes['height'] = $real_height;
            $resize_file_sizes['width'] = $real_width;
        }

        return $resize_file_sizes;
    }

    /**
     * @return array
     *               array(<pre>
     *               string, // 'jpeg'
     *               ...
     *               )</pre>
     */
    public static function getImageExtensions()
    {
        return array('jpeg', 'gif', 'png', 'jpg');
    }

    /**
     * @param array $file
     *                    array(<pre>
     *                    'name' => string,
     *                    'type' => string,
     *                    'tmp_name' => string,
     *                    'error' => string,
     *                    'size' => float
     *                    )</pre>
     * @reuturn string
     */
    public static function getFileExtension(array $file)
    {
        return pathinfo($file['name'], PATHINFO_EXTENSION);
    }
}
