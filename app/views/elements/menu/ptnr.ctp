<div id="menu">
    <div id="navigation" class="navigation">   
        <input type="radio" id="nav2" name="navigation" onclick="return loadPage('dashboards/')"><label for="nav2">My Dashboard</label>
        <input type="radio" id="nav3" name="navigation" onclick="return loadPage('leads/myopportunities')"><label for="nav3">My Portfolio</label>
        <input type="radio" id="nav7" name="navigation" onclick="return loadPage('companies/')"><label for="nav7">Organizations</label>
        <input type="radio" id="nav8" name="navigation" onclick="return loadPage('documents/')"><label for="nav8">Documents</label>
    </div>

    <div id="logged" class="logged">
        <button class="button" onclick="userEdit(<?php echo $session->read('Auth.User.id'); ?>);" title="Edit your profile"><img src="img/icons/profile.png"/></button>
        <button class="button" onclick="logout();" title="Logout from PlanningForce CRM"><img src="img/icons/exit.png"/></button>
    </div>
</div>