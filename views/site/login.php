<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
?>
<div class="site-login">
    <style>
        .form-signin {
            max-width: 330px;
            padding: 5px 15px 15px 15px;
            margin: 0 auto;
        }

        .form-signin .form-signin-heading, .form-signin .checkbox {
            margin-bottom: 10px;
        }

        .form-signin .checkbox {
            font-weight: normal;
        }

        .form-signin .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .account-wall {
            margin-top: 20px;
            padding: 20px 0px 20px 0px;
            background-color: #f7f7f7;
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        }

        .login-title {
            color: #555;
            font-size: 18px;
            font-weight: 400;
            display: block;
        }

        .profile-img {
            width: 96px;
            height: 96px;
            margin: 0 auto 10px;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

        div.account-wall div.lockglyph {
            text-align: center;
            font-size: 70px;
            height: 80px;
        }

        div.account-wall div.lockglyph i.glyphicon {
            color: rgb(215, 215, 215);
        }

        img.mainlogo {
            filter: url(<?= Yii::$app->urlManager->baseUrl ?>/images/drop-shadow.svg#drop-shadow);
        }

        div.loginPlaceHolder {
            max-width: 330px;
            padding: 15px 15px 0px 15px;
            margin: 0 auto;
            font-family: 'Jura', sans-serif;
            font-size: 13px;
            font-weight: bold;
            color: #8f8f8f;
        }

    </style>
    <div class="row" style="text-align: center">
        <img src="<?= Yii::$app->urlManager->baseUrl ?>/images/logo.png" class="mainlogo">
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <!--  <h1 class="text-center login-title">Введите логин и пароль для входа в систему:</h1>-->
            <div class="account-wall">
                <!--   <img class="profile-img" src="images/login.jpg" alt=""> -->
                <!--    <div class="lockglyph"><i class="glyphicon glyphicon-lock"></i></div>-->
                <div class="loginPlaceHolder" >Введите логин и пароль для входа в систему:</div>
                <?php
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'form-signin'],
                    'fieldConfig' => [
                        // 'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                        'template' => "{input}{error}",
                        //  'template' => "<div class=\"col-lg-3\"></div>{label}\n<div class=\"col-lg-3\">{input}</div><div class=\"col-lg-3\"></div>\n<div class=\"col-lg-11\">{error}</div>",
                        //  'labelOptions' => ['class' => 'col-lg-1 control-label'],
                    ],
                ]);
                ?>

                <?= $form->field($model, 'username', ['enableClientValidation' => false])->textInput(['placeholder' => 'Логин', 'autofocus' => true]) ?>

                <?= $form->field($model, 'password', ['enableClientValidation' => false])->passwordInput(['placeholder' => 'Пароль']) ?>

                <?=
                $form->field($model, 'rememberMe')->checkbox([
                    'template' => "{input} {label}",
                ])->label('Запомнить')
                ?>

                <?= Html::submitButton('Войти', ['class' => 'btn btn-lg btn-primary btn-block', 'name' => 'login-button']) ?>


                <?php ActiveForm::end(); ?>


                <!--   <form class="form-signin">
                       <input type="text" class="form-control" placeholder="Email" required autofocus>
                       <input type="password" class="form-control" placeholder="Password" required>
                       <button class="btn btn-lg btn-primary btn-block" type="submit">
                           Sign in</button>
                       <label class="checkbox pull-left">
                           <input type="checkbox" value="remember-me">
                           Remember me
                       </label>
                       <span class="clearfix"></span>
                   </form> -->
            </div>
        </div>
    </div>
</div>
