<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php echo $html->css('login'); ?>
        <title>Please login</title>
    </head>

    <body>
        <div id="login">            
            <form id="UserLoginForm" method="post" action="login" accept-charset="utf-8">
                <div style="display:none;"><input type="hidden" name="_method" value="POST" /></div>
                <input name="data[User][username]" type="text" id="resource" maxlength="255" />
                <input type="password" name="data[User][password]" id="password" />
                <input type="submit" id="connect" value="" />
            </form>
            <div style="float: right;margin:40px 10px 0 0">
                <span>Login</span> | <a href="openid">OpenID</a>
            </div>
        </div>
        <div id="error">
            <?php
            echo $session->flash();
            echo $session->flash('auth');
            ?>
        </div>
    </body>
</html>