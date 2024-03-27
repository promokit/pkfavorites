<?php

/**
 * Promokit Favorites
 *
 * @package   Promokit
 * @version   3.0.0
 * @author    https://promokit.eu
 * @copyright Copyright Ⓒ Since 2011 promokit.eu <@email:support@promokit.eu>
 * @license   You only can use the module, nothing more!
 */
declare(strict_types=1);

namespace Promokit\Module\Pkfavorites\FileManager;

use Promokit\Module\Pkfavorites\ResponseHandler\ResponseHandler;

class FileManager
{
    public function writeToFile(string $data, string $file): array
    {
        if (!$fileHolder = @fopen($file, 'w')) {
            return ResponseHandler::error("Unable to open file {$file}");
        }

        if (!fwrite($fileHolder, $data)) {
            fclose($fileHolder);
            return ResponseHandler::error("Unable to write file {$file}");
        }

        fclose($fileHolder);

        return ResponseHandler::success('Success');
    }

    public function readFile(string $file): string
    {
        if (!$f = @fopen($file, 'r')) {
            return ResponseHandler::error("Unable to open file {$file}");
        }

        if (file_exists($file) && filesize($file) !== false) {
            $code = @fread($f, filesize($file));
            fclose($f);
        }

        return $code ?? '';
    }
}
