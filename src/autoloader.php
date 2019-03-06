<?php
/**
 * Manual Autoloader File (if composer isn't installed)
 *
 * @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
 * @copyright      (c) 2017-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

namespace PH7\Eu;

// Autoloading Classes Files
spl_autoload_register(function ($sClass) {
    // Hack to remove namespace and backslash
    $sClass = str_replace([__NAMESPACE__ . '\\', '\\'], DIRECTORY_SEPARATOR, $sClass);

    // Get library classes
    if (is_file(__DIR__ . $sClass . '.php')) {
        require_once __DIR__ . $sClass . '.php';
    }
});
