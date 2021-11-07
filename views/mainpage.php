<?php 

/** @var $this \app\core\View */

$this->title = "Home";

    use app\core\Application;


?>

<h1>Main Page</h1>

<h2>Welcome to my Assignment 2 main page!</h2>

<hr>

<?php if (!Application::isVisitor()): ?>
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                <p class="h4">You are logged in as a <span class="fw-bold"><?php echo Application::$app->user->type ?></span></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>