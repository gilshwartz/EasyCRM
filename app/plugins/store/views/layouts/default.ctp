<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <?php
        echo $html->charset('utf-8');
        echo $content_for_layout;
        echo $this->Session->flash('auth');
        echo $session->flash('auth');
        echo $this->element('sql_dump');
        ?>
</html>
