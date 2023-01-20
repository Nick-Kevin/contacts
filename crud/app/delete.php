<?php
    include 'functions.php';

    $pdo = pdo_connect_mysql();
    $msg = '';

    if(isset($_GET['id'])) {
        $stmt    = $pdo->prepare("SELECT * FROM contacts WHERE id=?");
        $stmt->execute([$_GET['id']]);

        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$contact){
            exit('Contact doesn\'t exist with that Id');
        }

        if($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare("DELETE FROM contacts WHERE id=?");
            $stmt->execute([$_GET['id']]);
            $msg  = 'You have deleted the contact';
        }
    } else {
        exit('No ID specified');
    }
?>

<?=template_header('Delete');?>

    <div class="content delete">
        <h2>Delete contact where id = <?=$contact['id']?></h2>
        <?php if ($msg): ?>
            <p><?=$msg?></p>
        <?php else: ?>
            <p>Are you sure to delete this contact</p>
            <div class="yesno">
                <a href="delete.php?id=<?=$contact['id']?>&confirm=yes">Yes</a>
                <a href=read.php?">No</a>
            </div>
        <?php  endif;?>
    </div>

<?=template_footer();?>