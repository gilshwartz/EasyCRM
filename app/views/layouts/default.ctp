<?php ob_clean();?>
<!DOCTYPE html>
<html>
        <?php
        echo $content_for_layout;
        echo $this->Session->flash('auth');
        echo $session->flash('auth');
        echo $this->element('sql_dump');
        ?>
</html>