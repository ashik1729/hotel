<?php

/**
 * @link http://www.diemeisterei.de/
 *
 * @copyright Copyright (c) 2015 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace backend\modules\products;

use yii\console\Application;

/**
 * Class Module.
 *
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 *
 * @property mixed|object $localizedRootNode
 */
class Module extends \yii\base\Module {

    public function init() {
        parent::init();

        $this->params['foo'] = 'bar';
        // ...  other initialization code ...
    }

}
