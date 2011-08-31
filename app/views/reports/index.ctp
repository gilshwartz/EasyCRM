<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style>
            select{
                width:400px;
                margin:0 0 5px 0;
            }
            table.trials td{
                padding:3px 3px 3px 0
            }

            table.trials td.title{
                border-top: 1px solid #aee756;
                border-bottom: 1px solid #aee756;
                color: #525252;
                font-weight: bold;
                background: #e9fad0;
                border-right:none;
                padding:3px
            }
            td#name {
                width: 700px;
            }
        </style>
    </head>

    <body>
        <div class="div1000">
            <h1>Reports</h1>
            <table class="trials" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="title" colspan="4">Leads Management</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Trial Requests</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/leadstrialrequests');" class="button">View</a>
                        <a href="reports/export/leadstrialrequests.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/leadstrialrequests.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/leadstrialrequests.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Contact Requests</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/leadscontactrequests');" class="button">View</a>
                        <a href="reports/export/leadscontactrequests.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/leadscontactrequests.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/leadscontactrequests.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Express Downloads</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/leadsexpressrequests');" class="button">View</a>
                        <a href="reports/export/leadsexpressrequests.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/leadsexpressrequests.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/leadsexpressrequests.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td class="title" colspan="4">Opportunities</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Invoices</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/invoices');" class="button">View</a>
                        <a href="reports/export/invoices.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/invoices.pdf" target="_blank" class="button">PDF</a>
                        <a href="reports/export/invoices.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/invoices.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Wons</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/wons');" class="button">View</a>
                        <a href="reports/export/wons.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/wons.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/wons.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Losts</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/losts');" class="button">View</a>
                        <a href="reports/export/losts.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/losts.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/losts.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Activities</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/logs');" class="button">View</a>
                        <a href="reports/export/logs.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/logs.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/logs.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td class="title"  colspan="4">Contacts</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Export for newsletter</td>
                    <td></td>
                    <td>
                        <a href="reports/export/newslettercontacts.html" target="_blank" class="button">HTML</a>
                        <a href="reports/export/newslettercontacts.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/newslettercontacts.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/newslettercontacts.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Export for Community Newsletter</td>
                    <td></td>
                    <td>
                        <a href="reports/export/newslettercontacts/community.html" target="_blank" class="button">HTML</a>
                        <a href="reports/export/newslettercontacts/community.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/newslettercontacts/community.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/newslettercontacts/community.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Export Express Users</td>
                    <td></td>
                    <td>
                        <a href="reports/export/expressusers.html" target="_blank" class="button">HTML</a>
                        <a href="reports/export/expressusers.xls" target="_blank" class="button">Excel</a>
                        <a href="reports/export/expressusers.csv" target="_blank" class="button">CSV</a>
                        <a href="reports/export/expressusers.xml" target="_blank" class="button">XML</a>
                    </td>
                </tr>
                <tr>
                    <td class="title"  colspan="4">Requests</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Trials</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/trialrequests');" class="button">View</a>
                        <a href="reports/export/trialrequests.html" target="_blank" class="button">HTML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Free Express Download</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/expressrequests');" class="button">View</a>
                        <a href="reports/export/expressrequests.html" target="_blank" class="button">HTML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Contacts</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/contactrequests');" class="button">View</a>
                        <a href="reports/export/contactrequests.html" target="_blank" class="button">HTML</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td id="name">Consulting</td>
                    <td></td>
                    <td>
                        <a href="#" onclick="loadPage('reports/export/consultingrequests');" class="button">View</a>
                        <a href="reports/export/consultingrequests.html" target="_blank" class="button">HTML</a>
                    </td>
                </tr>
                
            </table>
        </div>
    </body>
</html>
