<?php
    include 'functions.php';

    $pdo = pdo_connect_mysql();
    $msg = '';
    global $nameErr, $titleErr, $emailErr, $phoneErr, $createdErr;

    if(!empty($_POST)) {
        $nameErr = $emailErr = $titleErr = $createdErr = $phoneErr = '';
        $name = $email = $title = '';
        
        $id      = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id']!='auto' ? $_POST['id'] : null;
        $phone   = isset($_POST['phone']) ? $_POST['phone'] : '';
        $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            if(empty($_POST['names'])) {
                $nameErr = 'Name is required';
            } else {
                $name    = process($_POST['names']);
                if(!preg_match("/^[a-zA-Z ]*$/", $name)) {
                    $nameErr        ='Invalid name';
                    $_POST['names'] = null;
                    $name           = '';
                }
            }

            if(empty($_POST['email'])) {
                $emailErr = 'Email is required';
            } else {
                $email    = process($_POST['email']);
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr       = 'Invalid email format';
                    $_POST['email'] = null;
                    $email          = '';
                }
            }

            if(empty($_POST['title'])) {
                $titleErr = 'Title is required';
            } else {
                $title    = process($_POST['title']);
                if(!preg_match("/^[a-zA-Z]*$/", $title)) {
                    $titleErr       = 'Invalid title';
                    $_POST['title'] = null;
                    $title          = '';
                }
            }

            if(!preg_match("/^(032|\+26132|033|\+26133|034|\+26134)\d{7}$/", $phone)) {
                $phoneErr       = 'Invalid phone format';
                $_POST['phone'] = null;
                $phone          = '';
            }
        }

        if($name == '' || $email == '' || $title == '' || $created == date('Y-m-d H:i:s') || $phone == '') {
            $id      = null;
            $email   = null;
            $title   = null;
            $phone   = null;
            $created = null;

            $msg     = 'There is an error';
        }

        $stmt    = $pdo->prepare("INSERT INTO contacts VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $name, $email, $phone, $title, $created]);

        if($msg == '') {
            $msg = 'Created succesfully';
        }

    }
?>

<?=template_header('create');?>

<div class="content update">
    <h2>Create contact</h2>
    <form action="create.php" method="POST">
        <label for="id">ID</label>
        <label for="names">Name:</label>
        <span class="error"><?=$nameErr;?></span>
        <input type="text" name="id" placeholder="26" value="auto" id="id">
        <input type="text" name="names" placeholder="Nick Kevin" id="names">
        <label for="phone">Phone:</label>
        <span class="error"><?=$phoneErr;?></span>
        <label for="email">Email:</label>
        <span class="error"><?=$emailErr;?></span>
        <input type="text" name="phone" placeholder="032 89 236 11" id="phone">
        <input type="text" name="email" placeholder="nicblou23@gmail.com" id="email">
        <label for="created">Created</label>
        <label for="title">Title:</label>
        <span class="error"><?=$titleErr?></span>
        <input type="datetime-local" name="created" placeholder="<?=date('Y-m-d\TH:i')?>" id="created">
        <input type="text" name="title" placeholder="Student" id="title">
        <input type="submit" value="Create"> 
    </form>
    <?php if($msg): ?>
        <p class="error"><?php$msg;?></p>
    <?php endif; ?>
</div>

<?=template_footer();?>