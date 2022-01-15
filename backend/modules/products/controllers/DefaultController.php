<?php

/**
 * @link http://www.diemeisterei.de/
 *
 * @copyright Copyright (c) 2015 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace backend\modules\products\controllers;

use Yii;
use yii\base\Event;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\View;

/**
 * Class DefaultController
 * @package dmstr\modules\pages\controllers
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class DefaultController extends Controller {

    /**
     * @inheritdoc
     */
    public function init() {
        if (\Yii::$app->user->can('pages', ['route' => true])) {
            \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
        }

        parent::init();
    }

    /**
     * @return mixed
     */
    public function actionIndex() {
        $queryTree = [];
        return $this->render('index', ['queryTree' => $queryTree]);
    }

    /**
     * @return \yii\web\Response
     * @throws MethodNotAllowedHttpException
     * @throws \yii\base\InvalidConfigException
     */
}
