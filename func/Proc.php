<?php

namespace app\func;

use Yii;
use yii\web\HttpException;
use yii\web\Session;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\db\ActiveRecord;
use yii\base\Model;

class Proc {

    public static function Breadcrumbs($view, $param = null) {
        if (isset($view)) {
            $param = $param === null ? [] : $param;

            $clearbefore = isset($param['clearbefore']) && is_bool($param['clearbefore']) ? $param['clearbefore'] : false;
            $addfirst = isset($param['addfirst']) && is_array($param['addfirst']) ? $param['addfirst'] : [];

            $postfix = isset($param['postfix']) ? $param['postfix'] : '';
            $id = $view->context->module->controller->id . '/' . $view->context->module->requestedRoute . '/' . $postfix;

            $session = new Session;
            $session->open();

            if (!isset($session['breadcrumbs']))
                $session['breadcrumbs'] = [];

            $result = $session['breadcrumbs'];

            if ($clearbefore)
                $result = [];

            if (count($addfirst) > 0) {
                $result = array_replace_recursive([
                    'addfirst' => $addfirst,
                        ], $result);
            }

            if (!isset($result[$id]))
                $result[$id] = [];

            $params = Yii::$app->getRequest()->getQueryParams();
            unset($params['r']);
            $result[$id] = array_replace_recursive($result[$id], [
                'label' => empty($view->title) ? '-' : $view->title,
                'url' => Url::toRoute(array_merge([$view->context->module->requestedRoute], $params)),
                'dopparams' => isset($_GET['foreignmodel']) ? [
                    'foreign' => [
                        'url' => (string) filter_input(INPUT_GET, 'url'),
                        'model' => (string) filter_input(INPUT_GET, 'foreignmodel'),
                        'field' => (string) filter_input(INPUT_GET, 'field'),
                        'id' => (string) filter_input(INPUT_GET, 'id'),
                    ]
                        ] : []
            ]);

            if (isset($param['model']) && !is_array($param['model']))
                $param['model'] = [$param['model']];

            if (isset($param['model']) && is_array($param['model'])) {
                foreach ($param['model'] as $model) {

                    $fmodel = substr($model->className(), strrpos($model->className(), '\\') + 1);

                    if (!isset($result[$id]['dopparams'][$fmodel])) {
                        $result[$id] = array_replace_recursive($result[$id], [
                            'dopparams' => [$fmodel => $model->attributes],
                        ]);
                    } else {
                        end($result);

                        $value = '';

                        $field = $result[key($result)]['dopparams']['foreign']['field'];

                        while (count($result) > 0 && $id !== key($result)) {
                            unset($result[key($result)]);
                            end($result);
                        }

                        $model->load($result[key($result)]['dopparams'], $fmodel);

                        if (isset(Yii::$app->request->get()[$fmodel][$field])) {
                            $value = Yii::$app->request->get()[$fmodel][$field];
                            $result[key($result)]['dopparams'][$fmodel][$field] = $value;
                            $model->$field = $value;
                        }
                    }
                }
            } else {
                end($result);
                while (count($result) > 0 && $id !== key($result)) {
                    unset($result[key($result)]);
                    end($result);
                }
            }

            $session['breadcrumbs'] = $result;

            /*  echo '<pre class="xdebug-var-dump" style="max-height: 350px; font-size: 15px;">';
              $s1 = $_SESSION;
              unset($s1['__flash']);
              print_r($s1);
              echo '</pre>'; */

            $session->close();

            end($result);

            unset($result[key($result)]['url']);

            return $result;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function Breadcrumbs');
    }

    // Настройки по умолчанию для DynaGrid
    public static function DGopts($Options) {
        if (isset($Options) && is_array($Options))
            return array_replace_recursive([
                'options' => ['id' => 'dynagrid-1'],
                'showPersonalize' => true,
                'storage' => 'cookie',
                //'allowPageSetting' => false, 
                'allowThemeSetting' => false,
                'allowFilterSetting' => false,
                'allowSortSetting' => false,
                'gridOptions' => [
                    'options' => ['id' => $Options['options']['id'] . '_gw'],
                    'panel' => [
                        'type' => Yii::$app->params['GridHeadingStyle'],
                        'headingOptions' => ['class' => 'panel-heading panel-' . Yii::$app->params['GridHeadingStyle']],
                    ],
                ],
                    ], $Options);
    }

    // Возвращает массив колонок для DynaGrid
    // $params['columns'] - массив полей базы данных для грида
    // $params['buttons'] - Кнопки для ActionColumn
    // $params['buttons']['update' => [0 => URL для обновления записи, 1 => ID обновляемой записи]]
    // $params['buttons']['delete' => [0 => URL для удаления записи, 1 => ID удаляемой записи]]
    public static function DGcols($params) {
        if (isset($params) && is_array($params)) {
            // Делаем строку 'template' на основе массива кнопок
            $tmpl = isset($params['buttons']) && is_array($params['buttons']) ? '{' . implode("} {", array_keys($params['buttons'])) . '}' : '';

            // Если есть кнопка обновления записи
            if (isset($params['buttons']['update']) && is_array($params['buttons']['update'])) {
                $params['buttons']['update'] = function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl([$params['buttons']['update'][0], 'id' => $model[$params['buttons']['update'][1]]]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                };
            }

            // Если есть кнопка удаления записи
            if (isset($params['buttons']['delete']) && is_array($params['buttons']['delete'])) {
                $params['buttons']['delete'] = function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl([$params['buttons']['delete'][0], 'id' => $model[$params['buttons']['delete'][1]]]);
                    return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'id' => 'Authitemexcel',
                                'type' => 'button',
                                'title' => 'Удалить',
                                'class' => 'btn btn-xs btn-danger',
                                'onclick' => 'ConfirmDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '")'
                    ]);
                };
            }

            return array_merge([
                ['class' => 'kartik\grid\SerialColumn',
                    'header' => Html::encode('№'),
                ]
                    ], isset($params['columns']) && is_array($params['columns']) ? $params['columns'] : [], isset($params['buttons']) && is_array($params['buttons']) && count($params['buttons'] > 0) ? [
                        [ 'class' => 'kartik\grid\ActionColumn',
                            'header' => Html::encode('Действия'),
                            'contentOptions' => ['style' => 'white-space: nowrap;'],
                            'template' => $tmpl,
                            'buttons' => is_array($params['buttons']) ? $params['buttons'] : [],]
                            ] : []);
        }
    }

    // Возвращает параметры для элемента Select2
    public static function DGselect2($params) {
        if (isset($params) && is_array($params)) {
            $model = $params['model'];
            $resultmodel = $params['resultmodel'];
            $resultrequest = $params['resultrequest'];
            $placeholder = $params['placeholder'];
            $fromgridroute = $params['fromgridroute'];
            $thisroute = $params['thisroute'];
            $fields = $params['fields'];
            $dopparams = $params['dopparams'];

            if (!isset($fields['showresultfields']))
                $fields['showresultfields'] = [$fields['resultfield']];

            if (!empty($model) && !empty($resultmodel) && !empty($fields['keyfield']) && !empty($fields['resultfield']) && !empty($fromgridroute) && !empty($thisroute)) {

                $initrecord = empty($model->$fields['keyfield']) ? '' : $resultmodel::find()
                                ->select($fields['showresultfields'])
                                ->where([$resultmodel->primarykey()[0] => $model->$fields['keyfield']])
                                ->asArray()
                                ->one();

                return [
                    'initValueText' => empty($model->$fields['keyfield']) ? '' : implode(', ', $initrecord),
                    'options' => ['placeholder' => $placeholder, 'class' => 'form-control setsession'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => Url::to([$resultrequest]),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term, field: "' . $fields['resultfield'] . '", showresultfields: ' . json_encode($fields['showresultfields']) . ' } }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    ],
                    'addon' => [
                        'append' => [
                            'content' => Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', array_merge([$fromgridroute,
                                'foreignmodel' => substr($model->className(), strrpos($model->className(), '\\') + 1),
                                'url' => $thisroute,
                                'field' => $fields['keyfield'],
                                'id' => $model->primaryKey,
                                            ], !is_array($dopparams) ? [] : $dopparams), ['class' => 'btn btn-success']),
                            'asButton' => true
                        ]
                    ]
                ];
            } else
                throw new \Exception('Ошибка в Proc::DGselect2()');
        }
    }

    // Выводит массив данных для Select2 элемента
    // $model - Модель из которой берутся данные
    // $field - Поле по которому осуществляется поиск
    // $q - Текстовая строка поиска
    // $showresultfields - Массив полей, которые возвращаются, как результат поиска
    public static function select2request($model, $field, $q = null, $showresultfields = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!isset($showresultfields))
            $showresultfields = [$field];

        $showresultfields = implode(', ', $showresultfields);
        if (!is_null($q)) {
            $m = new $model;
            $out['results'] = $model::find()
                    ->select([$m->primaryKey()[0] . ' AS id', 'CONCAT_WS(", ", ' . $showresultfields . ') AS text'])
                    ->where(['like', $field, $q])
                    ->limit(20)
                    ->asArray()
                    ->all();
        }
        return $out;
    }

    // Удаляет последний элемент массива хлебных крошек из сессии
    public static function RemoveLastBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        unset($bc[key($bc)]);
        $session['breadcrumbs'] = $bc;
        $session->close();
    }

    // Возвращает массив хлебных крошек из сессии
    public static function GetBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        $session->close();
        return $bc;
    }

    // Возвращает последний элемент хлебных крошек из сессии
    public static function GetLastBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        $session->close();
        return $bc[key($bc)];
    }

    public static function GetMenuButtons($view) {
        $controller = Yii::$app->controller;
        $default_controller = Yii::$app->defaultRoute;
        $isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;

        $urls = [
            'fregat_matcen' => 'Fregat/mattraffic/index',
            'fregat_conf' => 'Fregat/fregat/config',
            'config_conf' => 'Config/config/index',
        ];

        $session = new Session;
        $session->open();

        foreach ($urls as $url) // Записываем в сессию url меню
            if ($url === $view->context->module->requestedRoute) {
                $session['currentmenuurl'] = $url;
                break;
            }

        $result = [];
        if (!$isHome) {
            $menubuttons = isset($session['menubuttons']) ? $session['menubuttons'] : null;

            switch ($menubuttons) {
                case 'fregat':
                    $result = array_merge(
                            Yii::$app->user->can('FregatUserPermission') ? [['label' => 'Материальные ценности', 'url' => [$urls['fregat_matcen']],
                            'options' => $session['currentmenuurl'] === $urls['fregat_matcen'] ? ['class' => 'active'] : []
                                ]] : [], Yii::$app->user->can('FregatUserPermission') ? [['label' => 'Настройки', 'url' => [$urls['fregat_conf']],
                            'options' => $session['currentmenuurl'] === $urls['fregat_conf'] ? ['class' => 'active'] : []
                                ]] : []
                    );
                    break;
                case 'config':
                    $result = array_merge(
                            Yii::$app->user->can('UserEdit') || Yii::$app->user->can('RoleEdit') ? [['label' => 'Настройки портала', 'url' => [$urls['config_conf']],
                            'options' => $session['currentmenuurl'] === $urls['config_conf'] ? ['class' => 'active'] : [],
                                ]] : []
                    );
                    break;
            }
        } else {
            $session->remove('menubuttons');
            $session->remove('currentmenuurl');
        }
        $session->close();
        return $result;
    }

    public static function SetMenuButtons($ButtonsGroup) {
        $session = new Session;
        $session->open();
        $session['menubuttons'] = $ButtonsGroup;
        $session->close();
    }

    static function mb_preg_match_all($ps_pattern, $ps_subject, &$pa_matches, $pn_flags = PREG_PATTERN_ORDER, $pn_offset = 0, $ps_encoding = NULL) {
        // WARNING! - All this function does is to correct offsets, nothing else:

        if (is_null($ps_encoding))
            $ps_encoding = mb_internal_encoding();

        $pn_offset = strlen(mb_substr($ps_subject, 0, $pn_offset, $ps_encoding));
        $ret = preg_match_all($ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset);

        if ($ret && ($pn_flags & PREG_OFFSET_CAPTURE))
            foreach ($pa_matches as &$ha_match)
                foreach ($ha_match as &$ha_match)
                    $ha_match[1] = mb_strlen(substr($ps_subject, 0, $ha_match[1]), $ps_encoding);
        //
        // (code is independent of PREG_PATTER_ORDER / PREG_SET_ORDER)

        return $ret;
    }

    /**
     * Функция проверяет имя файла, если оно существует, в название добавляется порядковый номер, например Список.xls переходит в Список(1).xls
     * 
     * $fileroot - путь к файлу
     * возвращает новое имя файла
     */
    static function SaveFileIfExists($fileroot) {
        $counter = 1;
        $filename = substr($fileroot, strpos($fileroot, '/') + 1);

        while (file_exists($fileroot)) {
            preg_match('/(.+\/)(.+?)((\(.+)?\.)(.+)/i', $fileroot, $file_arr);
            // $file_arr[1] - Директория, $file_arr[2] - Имя файла, end($file_arr) - Расширение файла
            $fileroot = $file_arr[1] . $file_arr[2] . '(' . $counter . ')' . '.' . end($file_arr);
            $filename = $file_arr[2] . '(' . $counter . ')' . '.' . end($file_arr);
            $counter++;
        }

        return $filename;
    }

    static function WhereCunstruct($modelsearch, $field, $type = '') {
        preg_match('/(>=|<=|>|<|=)?(.*)/', $modelsearch->$field, $matches);
        $operator = $matches[1];
        $value = $matches[2];

        if ($type === 'date')
            $value = !empty($value) ? date("Y-m-d", strtotime($value)) : $value;
        elseif ($type === 'datetime')
            $value = !empty($value) ? date("Y-m-d H:i:s", strtotime($value)) : $value;
        elseif ($type === 'time')
            $value = !empty($value) ? date("H:i:s", strtotime($value)) : $value;

        return [empty($operator) ? '=' : $operator, $field, $value];
    }

    static function Translit($string) {
        $replace = array(
            "'" => "",
            "`" => "",
            "а" => "a", "А" => "a",
            "б" => "b", "Б" => "b",
            "в" => "v", "В" => "v",
            "г" => "g", "Г" => "g",
            "д" => "d", "Д" => "d",
            "е" => "e", "Е" => "e",
            "ж" => "zh", "Ж" => "zh",
            "з" => "z", "З" => "z",
            "и" => "i", "И" => "i",
            "й" => "y", "Й" => "y",
            "к" => "k", "К" => "k",
            "л" => "l", "Л" => "l",
            "м" => "m", "М" => "m",
            "н" => "n", "Н" => "n",
            "о" => "o", "О" => "o",
            "п" => "p", "П" => "p",
            "р" => "r", "Р" => "r",
            "с" => "s", "С" => "s",
            "т" => "t", "Т" => "t",
            "у" => "u", "У" => "u",
            "ф" => "f", "Ф" => "f",
            "х" => "h", "Х" => "h",
            "ц" => "c", "Ц" => "c",
            "ч" => "ch", "Ч" => "ch",
            "ш" => "sh", "Ш" => "sh",
            "щ" => "sch", "Щ" => "sch",
            "ъ" => "", "Ъ" => "",
            "ы" => "y", "Ы" => "y",
            "ь" => "", "Ь" => "",
            "э" => "e", "Э" => "e",
            "ю" => "yu", "Ю" => "yu",
            "я" => "ya", "Я" => "ya",
            "і" => "i", "І" => "i",
            "ї" => "yi", "Ї" => "yi",
            "є" => "e", "Є" => "e"
        );
        return $str = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
    }

    public static function CreateLogin($Fullname) {
        preg_match('/(\w+)\s?(\w+)?\s?(\w+)?/ui', $Fullname, $matches);
        $result = '';

        if (!empty($matches[1]))
            $result .= ucfirst(Proc::Translit($matches[1]));
        if (!empty($matches[2]))
            $result .= ucfirst(Proc::Translit(mb_substr($matches[2], 0, 1, 'UTF-8')));
        if (!empty($matches[3]))
            $result .= ucfirst(Proc::Translit(mb_substr($matches[3], 0, 1, 'UTF-8')));

        $count = \app\models\Config\Authuser::find()
                ->where(['like', 'auth_user_login', $result . '%', false])
                ->count();

        return $count > 0 ? $result . $count : $result;
    }

    public static function file_exists_ci($file) {
        if (file_exists($file))
            return $file;
        $lowerfile = strtolower($file);
        foreach (glob(dirname($file) . '/*') as $file)
            if (strtolower($file) == $lowerfile)
                return $file;
        return FALSE;
    }

    public static function GetAllLabelsFromAR($DataProvider, $fields = null) {
        $cls_ar = class_exists($DataProvider->query->modelClass) ? new $DataProvider->query->modelClass : false;

        if ($cls_ar instanceof ActiveRecord) {

            if (!is_array($fields))
                $fields = $cls_ar->attributes;

            $labels = [];

            array_walk($fields, function($value, $key) use (&$labels, $cls_ar) {
                $keytmp = empty(strpos($key, '.')) ? $key : substr($key, strrpos($key, '.') + 1);
                $labels[$keytmp] = $cls_ar->getAttributeLabel($key);
            });
        }

        return $labels;
    }

    public static function GetAllDataFromAR($Activerecord, $fields = null, $data = null) {
        if (!is_array($fields))
            $fields = [];

        if (!is_array($data)) {
            $data = array_intersect_key($Activerecord->toArray(), $fields);
            /*    $tmp2 = [];
              array_walk($data, function(&$value, $key) use (&$tmp2, $Activerecord) {
              $tmp2[$key] = [
              'label' => isset($Activerecord[$key]) ? $Activerecord->getAttributeLabel($key) : $key,
              'value' => $value
              ];
              });

              $data = $tmp2; */
        }

        foreach ($Activerecord->getRelatedRecords() as $relat => $ar_relat)
            if ($ar_relat instanceof ActiveRecord) {
                $tmp = [];

                array_walk($fields, function(&$value, $key) use ($relat, &$tmp) {
                    if (strpos($key, $relat) === 0)
                        $key = substr($key, strlen($relat) + 1);
                    $tmp[$key] = $value;
                });

                $dop = array_intersect_key($ar_relat->toArray(), $tmp);

                /*    $tmp3 = [];
                  array_walk($dop, function(&$value, $key) use (&$tmp3, $ar_relat) {
                  $tmp3[$key] = [
                  'label' => isset($ar_relat[$key]) ? $ar_relat->getAttributeLabel($key) : $key,
                  'value' => $value
                  ];
                  });

                  $dop = $tmp3; */

                $data = array_merge($data, $dop);
                $data = self::GetAllDataFromAR($ar_relat, $tmp, $data);
            }

        return $data;
    }

    public static function Grid2Excel($dataProvider, $modelName, $reportName, $selectvalues = NULL, $ModelFilter = NULL) {
        $objPHPExcel = new \PHPExcel;

        /* Границы таблицы */
        $ramka = array(
            'borders' => array(
                'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'top' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        );

        /* Жирный шрифт для шапки таблицы */
        $font = array(
            'font' => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );

        //      var_dump(Yii::$app->request->queryParams);

        $params = Yii::$app->request->queryParams;
        $inputdata = json_decode($params['inputdata']);
        $fields = Proc::GetArrayValuesByKeyName($modelName, $inputdata);
        $selectvalues = (array) $selectvalues;

        $dataProvider->pagination = false;
        $labels = self::GetAllLabelsFromAR($dataProvider, $fields[$modelName]);
        $filter = 'Фильтр:';

        foreach ($fields[$modelName] as $attr => $value) {
            $val_result = $value;
            if (!empty($value)) {
                $attrlabel = strpos($attr, '.') === false ? $attr : substr($attr, strrpos($attr, '.') + 1);

                if (isset($selectvalues[$modelName . '[' . $attr . ']']))
                    $val_result = $selectvalues[$modelName . '[' . $attr . ']'][$fields[$modelName][$attr]];

                $filter .= ' ' . $labels[$attrlabel] . ': "' . $val_result . '";';
            }
        }

        if ($ModelFilter instanceof Model) {
            $dopfilter = self::ConstructFilterOutput($ModelFilter);
            if (!empty($dopfilter))
                $filter .= ' ' . $dopfilter;
        }

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $reportName);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        ]);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Дата: ' . date('d.m.Y'));
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 2)->applyFromArray([
            'font' => [
                'italic' => true
            ]
        ]);

        $i = -1;
        $r = 5;
        foreach ($labels as $attr => $label) {
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, $label);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r)->applyFromArray($ramka);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r)->applyFromArray($font);
        }

        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, $i, 1);

        foreach ($dataProvider->getModels() as $ar) {
            $r++;
            $data = self::GetAllDataFromAR($ar, $fields[$modelName]);
            $i = -1;
            foreach (array_keys($labels) as $attr) {
                $i++;
                if (isset($selectvalues[$modelName . '[' . $attr . ']']))
                    $data[$attr] = $selectvalues[$modelName . '[' . $attr . ']'][$data[$attr]];
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, isset($data[$attr]) ? $data[$attr] : '');
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r)->applyFromArray($ramka);
            }
        }

        /* Авторазмер колонок Excel */
        $i = -1;
        foreach ($labels as $attr => $label) {
            $i++;
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
        }

        if ($filter !== 'Фильтр:') {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, $filter);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 3, $i, 3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray([
                'font' => [
                    'italic' => true
                ]
            ]);
        }

        // присваиваем имя файла от имени модели 
        $FileName = $reportName;

        // Устанавливаем имя листа
        $objPHPExcel->getActiveSheet()->setTitle($FileName);

        // Выбираем первый лист
        $objPHPExcel->setActiveSheetIndex(0);
        // Формируем файл Excel
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $FileName = DIRECTORY_SEPARATOR === '/' ? $FileName : mb_convert_encoding($FileName, 'Windows-1251', 'UTF-8');
        // Proc::SaveFileIfExists() - Функция выводит подходящее имя файла, которое еще не существует. mb_convert_encoding() - Изменяем кодировку на кодировку Windows
        $fileroot = self::SaveFileIfExists('files/' . $FileName . '.xlsx');
        // Сохраняем файл в папку "files"
        $objWriter->save('files/' . $fileroot);
        // Возвращаем имя файла Excel
        if (DIRECTORY_SEPARATOR === '/')
            echo $fileroot;
        else
            echo mb_convert_encoding($fileroot, 'UTF-8', 'Windows-1251');
    }

    // Функция преобразует массив
    /*
      AuthitemSearch[description] => string ''
      AuthitemSearch[type] => string ''
      AuthitemSearch[name] => string ''

      'description' => string ''
      'type' => string ''
      'name' => string ''
     */

    public static function GetArrayValuesByKeyName($KeyName, $Obj) {
        $result = [];
        if (is_string($KeyName) && (is_array($Obj) || is_object($Obj)))
            foreach ($Obj as $key => $value) {
                if (strpos($key, $KeyName) === 0) {
                    preg_match('/\[(.+)\]/', $key, $matches);
                    if (!empty($matches[1]))
                        $result[$matches[1]] = $value;
                }
            }
        return [$KeyName => $result];
    }

    // ФИЛЬТР в DYNAGRID-----------------------------------------------------------------------------------------------------------------
    // Устанавливаем фильтр из переденных параметров запроса и сохраняем в сессию, выводим строку фильтра для отображения в гриде
    public static function SetFilter($ModelGridName, $ModelFilter) {
        if (is_string($ModelGridName) && $ModelFilter instanceof Model) {
            $filter = '';
            $dofilterstring = false;
            $session = new Session;
            $session->open();
            $fmodel = substr($ModelFilter->className(), strrpos($ModelFilter->className(), '\\') + 1);
            if (isset(Yii::$app->request->queryParams[$ModelGridName]['_filter'])) {
                $filterfield = Yii::$app->request->queryParams[$ModelGridName]['_filter'];
                parse_str($filterfield, $filterparams);
                if (is_array($filterparams)) {
                    unset($filterparams['_csrf']);
                    $sestmp[$ModelGridName] = $filterparams;
                    $session['_filter'] = $sestmp;
                    $dofilterstring = true;
                }
            } elseif (isset(Yii::$app->request->queryParams[$ModelGridName]) && !isset(Yii::$app->request->queryParams[$ModelGridName]['_filter'])) {
                unset($session['_filter']);
            } elseif (isset($session['_filter']))
                $dofilterstring = true;

            if ($dofilterstring) {
                $filter = self::ConstructFilterOutput($ModelFilter);
                if (!empty($filter))
                    $filter = '<div class="panel panel-warning"><div class="panel-heading">Доп фильтр:' . $filter . '<button id="' . $fmodel . '_resetfilter" type="button" class="close" aria-hidden="true">&times;</button></div></div>';
            }

            $session->close();
            return $filter;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function SetFilter');
    }

    // Заполняем форму Фильтра из сессии
    public static function PopulateFilterForm($ModelGridName, &$ModelFilter) {
        if (is_string($ModelGridName) && $ModelFilter instanceof Model) {
            $ModelFilterName = substr($ModelFilter->className(), strrpos($ModelFilter->className(), '\\') + 1);
            $session = new Session;
            $session->open();
            if (isset($session['_filter'][$ModelGridName][$ModelFilterName]))
                $ModelFilter->load($session['_filter'][$ModelGridName]);
            $session->close();
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function PopulateFilterForm');
    }

    // Получаем значения полей фильтра
    public static function GetFilter($ModelGridName, $ModelFilterName) {
        if (is_string($ModelGridName) && is_string($ModelFilterName)) {
            $result = [];
            $session = new Session;
            $session->open();
            if (isset($session['_filter'][$ModelGridName][$ModelFilterName]) && is_array($session['_filter'][$ModelGridName][$ModelFilterName]))
                $result = $session['_filter'][$ModelGridName][$ModelFilterName];

            $session->close();
            return $result;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function GetFilter');
    }

    // Функция выводит строку фильтра для отображения в гриде
    private static function ConstructFilterOutput($AR) {
        $session = new Session;
        $session->open();
        $filter = '';

        if (isset($session['_filter'])) {
            foreach ($session['_filter'] as $filtform) {
                foreach ($filtform as $filtformname => $fields) {
                    $fmodel = substr($AR->className(), strrpos($AR->className(), '\\') + 1);
                    if ($filtformname === $fmodel) {
                        foreach ($fields as $attr => $value)
                            if (strpos($attr, '_mark') === strlen($attr) - 5) {
                                if ($value === '1')
                                    $filter .= ' ' . $AR->attributeLabels()[$attr] . ';';
                            } else
                                $filter .= ' ' . $AR->attributeLabels()[$attr] . ' = "' . $value . '"';
                    }
                }
            }
        }

        $session->close();
        return $filter;
    }

}
