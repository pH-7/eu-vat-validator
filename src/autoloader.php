<?php
/**
 * Manual Autoloader File (if composer isn't installed)
 *
 * @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
 * @copyright      (c) 2017, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

namespace PH7\Eu;

// Autoloading Classes Files
spl_autoload_register(function($sClass) {
    // Hack to remove namespace and backslash
    $sClass = str_replace(array(__NAMESPACE__ . '\\', '\\'), DIRECTORY_SEPARATOR, $sClass);

    // Get library classes
    if (is_file(dirname(__FILE__) . $sClass . '.php')) {
        require_once dirname(__FILE__) . $sClass . '.php';
    }
});
