<?php

use app\core\Application;

use app\core\form\Form;

require_once Application::$ROOT_DIR . '/core/form/Form.php';

$this->title = 'Admin';
?>


<div class="row justify-content-center">
    <div class="col-6">
        <p class="h1">Admin Page</p>
        <!-- Bootstrap card -->

        <div class="card shadow mt-4">
            <div class="card-header">
                <h3>All User Attributes</h3>
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
                        <?php foreach ($model->allUsers as $key => $value): ?>
                            <tr>
                                <th scope="row"><?php echo $value['id'] ?></th>
                                <td><?php echo $value["username"] ?></td>
                                <td><?php echo $value["type"] ?></td>
                                <td><?php echo $value["created_at"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header">
                <h3>Create User</h3>
            </div>
            <div class="card-body">
                <?php $form = Form::begin('', "POST") ?>

                <?php echo $form->field($model, 'username') ?>
                <?php echo $form->field($model, 'password')->passwordField() ?>
                <?php echo $form->field($model, 'passwordConfirm')->passwordField() ?>
                <button type="submit" class="btn btn-primary">Submit</button>

                <?php echo Form::end() ?>
            </div>
        </div>
    </div>
</div>