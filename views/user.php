<?php

use app\core\Application;

use app\core\form\Form;

require_once Application::$ROOT_DIR . '/core/form/Form.php';

$this->title = 'User';
?>


<div class="row justify-content-center">
    <div class="col-6">
        <p class="h1">User Page</p>
        <!-- Bootstrap card -->

        <div class="card shadow mt-4">
            <div class="card-header">
                <h3>User Attributes</h3>
            </div>
            <div class="card-body">
                <!-- bootstrap tables with username and type -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Username</th>
                            <th scope="col">User Type</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo Application::$app->user->id ?></th>
                            <td><?php echo Application::$app->user->username ?></td>
                            <td><?php echo Application::$app->user->type ?></td>
                            <td><?php echo Application::$app->user->created_at ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header">
                <h3>Change Password</h3>
            </div>
            <div class="card-body">
                <?php $form = Form::begin('', "POST") ?>

                <?php echo $form->field($model, 'password')->passwordField() ?>
                <?php echo $form->field($model, 'passwordConfirm')->passwordField() ?>
                <button type="submit" class="btn btn-primary">Submit</button>

                <?php echo Form::end() ?>
            </div>
        </div>
    </div>
</div>