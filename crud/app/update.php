<?php
    include 'functions.php';

    $pdo = pdo_connect_mysql();
    $msg = '';
    global $nameErr;
    $emailErr;
    // Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
    if (isset($_GET['id'])) {
        $nameErr = $emailErr = $titleErr = $phoneErr = '';

        if (!empty($_POST)) {
            // This part is similar to the create.php, but instead we update a record and not insert
            $id      = isset($_POST['id']) ? $_POST['id'] : NULL;
            $phone   = isset($_POST['phone']) ? $_POST['phone'] : '';
            $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');

            if(empty($_POST['names'])) {
                $nameErr = 'name is required';
            } else {
                $name    = $_POST['names'];
                if(!preg_match("/^[a-zA-Z ]*$/",$name)) {
                    $nameErr = 'Invalid name';
                }
            }

            if(empty($_POST['email'])) {
                $emailErr = 'email is required';
            } else {
                $email    = $_POST['email'];
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr       = 'Invalid email format';
                    $_POST['email'] = null;
                    $email          = '';
                }
            }

            if(empty($_POST['title'])) {
                $titleErr = 'Title is required';
            } else {
                $title    = $_POST['title'];
                if(!preg_match("/^[a-zA-Z]*$/", $title)) {
                    $titleErr       = 'Invalid title';
                    $title          = '';
                    $_POST['title'] = null;
                }
            }

            if(!preg_match("/^(034|\+26134|032|\+26132|033|\+26133|038|\+26138)\d{7}$/", $phone)) {
                $phoneErr = 'Invalid phone format';
            }

            // Update the record
            if($nameErr == '' && $emailErr == '' && $titleErr == '' && $phoneErr == '') {
                $stmt = $pdo->prepare('UPDATE contacts SET id = ?, names = ?, email = ?, phone = ?, title = ?, created = ? WHERE id = ?');
                $stmt->execute([$id, $name, $email, $phone, $title, $created, $_GET['id']]);
                $msg = 'Updated Successfully!';
            } else {
                $msg = 'Sorry, there is an error';
            }
        }
        // Get the contact from the contacts table
        $stmt    = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$contact) {
            exit('Contact doesn\'t exist with that ID!');
        }
    } else {
        exit('No ID specified!');
    }
?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Contact #<?=$contact['id']?></h2>
    <form action="update.php?id=<?=$contact['id']?>" method="post">
        <label for="id">ID</label>
        <label for="names">Name</label>
        <span class="error"><?=$nameErr?></span>
        <input type="text" name="id" placeholder="1" value="<?=$contact['id']?>" id="id">
        <input type="text" name="names" placeholder="John Doe" value="<?=$contact['names']?>" id="name">
        <label for="phone">Phone</label>
        <span class="error"><?=$phoneErr?></span>
        <label for="email">Email</label>
        <span class="error"><?=$emailErr?></span>
        <input type="text" name="phone" placeholder="+261320011122" value="<?=$contact['phone']?>" id="phone">
        <input type="text" name="email" placeholder="johndoe@example.com" value="<?=$contact['email']?>" id="email">
        <label for="title">Title</label>
        <label for="created">Created</label>
        <input type="text" name="title" placeholder="Employee" value="<?=$contact['title']?>" id="title">
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i', strtotime($contact['created']))?>" id="created">
        <span class="error"><?=$titleErr?></span>
        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>*/?>