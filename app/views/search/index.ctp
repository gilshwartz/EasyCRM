<div class="div1000">
    <h1 class="datepicker">Search results :</h1>
</div>
<table cellpadding="0" cellspacing="0" class="stripeme">
    <thead>
        <tr>
            <th>Type</th>
            <th>Name</th>
            <th></th>
            <th width="250px"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($results as $result) {
            $keys = array_keys($result);
            echo '<tr>';
            if ($keys[0] == 'Lead') {
                echo '<td>Lead</td>';
                echo '<td>' . $result['Lead']['name'] . '</td>';
                echo '<td></td>';
                echo '<td><button class="button" onclick="goToLead(' . $result['Lead']['id'] . ')">Details</button></td>';
            } else if ($keys[0] == 'License') {
                echo '<td>License</td>';
                echo '<td>' . $result['License']['serialkey'] . '</td>';
                echo '<td></td>';
                echo '<td>';
                echo '<button class="button" onclick="licenseDetails('.$result['License']['id'].')">View details</button>';
                echo '<button class="button" onclick="return loadPage(\'leads/view/' . $result['LicensesLead'][0]['leads_id'] . '\');">Show Lead</button>';
                echo '</td>';
            } else if ($keys[0] == 'Contact') {
                $emails = unserialize($result['Contact']['emails']);
                if ($emails === false) {
                    $emails[0] = $result['Contact']['emails'];
                }
                echo '<td>Contact</td>';
                echo '<td>' . $result['Contact']['fullname'] . '</td>';
                echo '<td>' . $emails[0] . '</td>';
                echo '<td>';
                echo '<button class="button" onclick="opportunityEditContact(' . $result['Contact']['id'] . ');">Details</button>&nbsp;&nbsp;';
                echo '<button class="button" onclick="contactRequests(' . $result['Contact']['id'] . ');">Requests</button>&nbsp;&nbsp;';
                echo '<button class="button" onclick="contactLeads(' . $result['Contact']['id'] . ');">Leads</button>&nbsp;&nbsp;';
                echo '<button class="button" onclick="contactDelete(' . $result['Contact']['id'] . ');">Delete</button>';
                echo '</td>';
            } else if ($keys[0] == 'Company') {
                echo '<td>Company</td>';
                echo '<td>' . $result['Company']['name'] . '</td>';
                echo '<td>' . $result['Country']['name'] . '</td>';
                echo '<td>';
                echo '<button class="button" onclick="companyEdit(' . $result['Company']['id'] . ')">Details</button>';
                echo '<button class="button" onclick="companyLeads(' . $result['Company']['id'] . ')">Related Leads</button>';
                echo '</td>';
            } else if ($keys[0] == 'Document') {
                echo '<td>Document</td>';
                echo '<td></td>';
                echo '<td>' . $result['Document']['name'] . '</td>';
                echo '<td><a href="documents/view/' . $result['Document']['id'] . '" class="button" >Download</a></td>';
            }  else if ($keys[0] == 'Request') {
                $emails = unserialize($result['Contact']['emails']);
                if ($emails === false) {
                    $emails[0] = $result['Contact']['emails'];
                }
                echo '<td>Request</td>';
                echo '<td>' . $result['Contact']['fullname'] . '</td>';
                echo '<td>' . $emails[0] . '</td>';
                echo '<td><button class="button" onclick="requestDetails(' . $result['Request']['id'] . ', true);">View details</button></td>';
            }
        }
        ?>
    </tbody>
</table>