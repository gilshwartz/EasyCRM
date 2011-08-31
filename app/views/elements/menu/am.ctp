<div id="menu">
    <div id="navigation" class="navigation">
        <input type="radio" id="nav1" name="navigation" onclick="return loadPage('dashboards/')"><label for="nav1">My Dashboard</label>
        <input type="radio" id="nav2" name="navigation" onclick="return loadPage('leads/myopportunities')"><label for="nav2">My Portfolio</label>
    </div>

    <div id="logged" class="logged">
        <button class="button" onclick="userEdit(<?php echo $session->read('Auth.User.id'); ?>);" title="Edit your profile"><img src="img/icons/profile.png"/></button>
        <button class="button" onclick="logout();" title="Logout from PlanningForce CRM"><img src="img/icons/exit.png"/></button>
    </div>
</div>