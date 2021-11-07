<?php

use app\core\Application;
use app\core\form\Form;

require_once Application::$ROOT_DIR . '/core/form/Form.php';
?>

<div class="row justify-content-center">
    <div class="col-6">
        <div class="card shadow">
            <div class="card-header">
                <p class="h2">Sign Up!</p>
            </div>
            <div class="card-body">
                <?php $form = Form::begin('', "POST") ?>

                <?php echo $form->field($signupModel, 'username') ?>
                <?php echo $form->field($signupModel, 'password')->passwordField() ?>
                <?php echo $form->field($signupModel, 'passwordConfirm')->passwordField() ?>
                <button type="submit" class="btn btn-primary">Submit</button>

                <?php echo Form::end() ?>
            </div>
        </div>
    </div>
</div>