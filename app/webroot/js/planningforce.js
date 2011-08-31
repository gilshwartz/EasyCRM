/*START MAIN SITE RELATED FUNCTIONS*/
var lastNbNewReq = 0;
var lastNbPendingReq = 0;
var page = null;
var formModified = false;

$(document).ready(function() {
    $.ajaxSetup ({
        // Disable caching of AJAX responses
        cache: false
    });
    $( "#navigation" ).buttonset();
    $( "#logged" ).buttonset();
    if (page == null && window.location.hash != "") {
        loadPage(window.location.hash.substr(1, window.location.hash.length));
    } else {
        if (type == "admin" || type == "lm")
            loadPage("requests/newrequests");
        else
            loadPage("dashboards/");
    }

    window.onhashchange = function(){
        var hash = window.location.hash.substr(1, window.location.hash.length);
        if (hash != page && hash != "")
            loadPage(hash);
    };
    getPendingRequest();
    /*SEARCH BAR DEFAULT VALUE AND ASPECT*/

    $("#searchbar").attr("value", "I'm looking for...");
    //$("#searchbar").attr("value", "Comming soon...");

    var text = "I'm looking for...";

    $("#searchbar").focus(function() {
        $(this).addClass("active");
        if($(this).attr("value") == text) $(this).attr("value", "");
    });

    $("#searchbar").blur(function() {
        $(this).removeClass("active");
        if($(this).attr("value") == "") $(this).attr("value", text);
    });
    getNewRequest();
    $('textarea[name="editor"]').ckeditor(editorConfig);
    pingAlive();
});

function download(file) {
    var url = "documents/view/" + file
    window.open(url);
}

function loadPage(url) {
    if (!formModified) {
        $('#webpage').load(url, function() {
            page = url;
            location.hash = url;
            stripme();
            $('#reminder').hide();
            $('#notes').hide();
            $(".button").button();
            $('form').submit(function () {
                return false;
            });
            setActiveMenu();
        });
    } else {
        $( "#alertUnsaved" ).dialog({
            resizable: false,
            width:350,
            modal: true,
            buttons: {
                "Yes": function() {
                    formModified = false;
                    $( this ).dialog( "destroy" );
                    loadPage(url);
                },
                Cancel: function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    }
    return false;
}

function setActiveMenu() {
    if (page === "dashboards/") {
        $('#navigation #nav2').attr('checked', 'checked');
        $('#navigation #nav2').button('refresh');
    }
    else if ($('#submenu').hasClass("portfolio")) {
        $('#navigation #nav3').attr('checked', 'checked');
        $('#navigation #nav3').button('refresh');
    }
    else if (page === "companies/") {
        $('#navigation #nav7').attr('checked', 'checked');
        $('#navigation #nav7').button('refresh');
    }
    else if (page === "documents/") {
        $('#navigation #nav8').attr('checked', 'checked');
        $('#navigation #nav8').button('refresh');
    }
    else if ($('#submenu').hasClass("management")) {
        $('#navigation #nav1').attr('checked', 'checked');
        $('#navigation #nav1').button('refresh');
    }
    else if ($('#submenu').hasClass("opportunities")) {
        $('#navigation #nav4').attr('checked', 'checked');
        $('#navigation #nav4').button('refresh');
    }
    else if (page === "reports") {
        $('#navigation #nav6').attr('checked', 'checked');
        $('#navigation #nav6').button('refresh');
    }
    else if (page === "emails") {
        $('#navigation #nav5').attr('checked', 'checked');
        $('#navigation #nav5').button('refresh');
    }
}

function stripme() {
    $(".stripeme tr:even").addClass("alt");
    $(".stripeme tr").mouseover(function(){
        $(this).addClass("over");
    }).mouseout(function(){
        $(this).removeClass("over");
    });
}

function logout(confirm) {
    if (!confirm) {
        $( "#logout" ).dialog({
            resizable: false,
            width:400,
            modal: true,
            buttons: {
                "Confirm": function() {
                    $( this ).dialog( "destroy" );
                    logout(true);
                },
                "Cancel": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        document.location = "authentifications/logout?" + new Date().getTime();
    }
}

function changeVisibility(divtochange) //This function shows or hide a div
{
    if($(divtochange).is(":visible")){
        $('.others').hide('fast');
        $(divtochange).hide('fast');
    }
    else{
        $('.others').hide('fast');
        $(divtochange).show('fast');
    }
}

function paginator() {
    $('#container a').each(function () {
        if ($(this).attr('href') != "#") {
            //var url = encodeURI($(this).attr('href'));
            var url = $(this).attr('href');
            $(this).attr('href', '#');
            $(this)[0].setAttribute("onclick", "loadPage('" + url + "')");
        }
    });
}

function pingAlive() {
    $.ajax({
        url: 'authentifications/alive',
        cache: false,
        success: function(data) {
            if (data == 'expired') {
                setTimeout('logout(true)', 1000 * 60);
                $( "#incativity" ).dialog({
                    resizable: false,
                    width:400,
                    modal: true,
                    buttons: {
                        "Disconnect": function() {
                            logout(true);
                            $( this ).dialog( "destroy" );
                        },
                        "Cancel": function() {
                            $.get('authentifications/alive/true');
                            $( this ).dialog( "destroy" );
                        }
                    }
                });
            }
        },
        error: function(data) {
            document.location = "authentifications/logout";
        }
    });
    setTimeout('pingAlive()', 1000 * 60 * 1);
}

function getNewRequest() {
    $.ajax({
        url: 'requests/newest.xml',
        cache: false,
        success: function(data) {
            var nbRequest = parseInt($('count', data).text());
            if (nbRequest > 0 && nbRequest != lastNbNewReq) {
                notify('You have received ' + nbRequest + ' new Request(s)!');
                lastNbNewReq = nbRequest;
                if (page === "dashboards/") {
                    $('#newrequestbanner strong').html(nbRequest);
                    $('#norequestbanner').hide('slow');
                    $('#newrequestbanner').show('slow');
                }
            } else if (nbRequest == 0 && nbRequest != lastNbNewReq) {
                lastNbNewReq = nbRequest;
                if (page === "dashboards/") {
                    $('#newrequestbanner strong').html(nbRequest);
                    $('#newrequestbanner').hide('slow');
                    if (lastNbPendingReq == 0)
                        $('#norequestbanner').show('slow');
                    
                }
            }
        }
    });
    setTimeout('getNewRequest()', 1000 * 60 * 2);
}

function getPendingRequest() {
    $.ajax({
        url: 'requests/pending.xml',
        cache: false,
        success: function(data) {
            var nbRequest = parseInt($('count', data).text());
            if (nbRequest > 0 && nbRequest != lastNbPendingReq) {
                notify('You have ' + nbRequest + ' pending Request(s)!');
                lastNbPendingReq = nbRequest;
                if (page === "dashboards/") {
                    $('#pendingrequestbanner strong').html(nbRequest);
                    $('#norequestbanner').hide('slow');
                    $('#pendingrequestbanner').show('slow');
                }
            } else if (nbRequest == 0 && nbRequest != lastNbPendingReq) {
                lastNbPendingReq = nbRequest;
                if (page === "dashboards/") {
                    $('#pendingrequestbanner strong').html(nbRequest);
                    $('#pendingrequestbanner').hide('slow');
                    if (lastNbNewReq == 0)
                        $('#norequestbanner').show('slow');
                    
                }
            }
        }
    });
    setTimeout('getPendingRequest()', 1000 * 60 * 2);
}

function notify(msg, title, type) {
    if (!title)
        type = 'Notification';
    if (!type)
        type = 'notice';
    $.pnotify({
        pnotify_title: type,
        pnotify_text: msg,
        pnotify_type: type,
        pnotify_animation: 'fade',
        pnotify_history: false
    });
}
/*END MAIN SITE RELATED FUNCTIONS*/


function assignlicense(){
    $( "#assignlicense" ).dialog({
        resizable: false,
        width:300,
        modal: true,
        buttons: {
            "Assign this license": function() {
            }
        }
    });
}



function deleteitem(){
    $( "#deleteitem" ).dialog({
        resizable: false,
        width:250,
        modal: true,
        buttons: {
            "Yes": function() {
            },
            Cancel: function() {
                $( this ).dialog( "destroy" );
            }
        }
    });
}

function markasdone(){
    $( "#markasdone" ).dialog({
        resizable: false,
        width:250,
        modal: true,
        buttons: {
            "Yes": function() {
            },
            Cancel: function() {
                $( this ).dialog( "destroy" );
            }
        }
    });
}

function taskupdate(){
    $( "#taskupdate" ).dialog({
        resizable: false,
        width:500,
        modal: true,
        buttons: {
            "Save changes": function() {
            },
            Cancel: function() {
                $( this ).dialog( "destroy" );
            }
        }
    });
}


function shownotesdetail(){
    $( "#shownotesdetail" ).dialog({
        resizable: false,
        width:500,
        modal: true,
        buttons: {
            "Delete": function() {
            },
            "Save changes": function() {
            }
        }
    });
}


function createreminder(){
    $( "#createreminder" ).dialog({
        resizable: false,
        width:400,
        modal: true,
        buttons: {
            Save: function() {
            }
        }
    });
}

function createnote(){
    $( "#createnote" ).dialog({
        resizable: false,
        width:400,
        modal: true,
        buttons: {
            Save: function() {
            }
        }
    });
}



/*Show license details*/
function showlicensedetails(){
    $( "#licensedetails" ).dialog({
        resizable: false,
        width:600,
        modal: true,
        buttons: {
            "Give offline license": function() {
            },
            "Reset license": function() {
            },
            "Replace this license": function() {
            },
            "Revoke license": function() {
            }
        }
    });
}


function userAdd(confirm) {
    if (!confirm) {
        $.ajax({
            url: 'users/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Create": function() {
                            $( this ).dialog( "destroy" );
                            userAdd(true);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'users/add',
            data: $('#user2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("User created");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function userDelete(id, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog('close');
                    userDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'users/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    loadPage(page, true);
                    notify("user has been deleted");
                }else {
                    notify ("Oups, an error occured ! Please try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function groupAdd(confirm) {
    if (!confirm) {
        $.ajax({
            url: 'groups/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Create": function() {
                            $( this ).dialog( "destroy" );
                            groupAdd(true);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'groups/add',
            data: $('#group2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("group created");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function groupDelete(id, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog('close');
                    groupDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'groups/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    loadPage(page, true);
                    notify("group has been deleted");
                } else {
                    notify (data, "Error", "error");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function groupEdit(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'groups/edit/' + id,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            groupEdit(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }else {
        $.ajax({
            url: 'groups/edit/' + id,
            data: $('#group2edit').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("group saved");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function productAdd(confirm) {
    if (!confirm) {
        $.ajax({
            url: 'settings/products/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Create": function() {
                            $( this ).dialog( "destroy" );
                            productAdd(true);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'settings/products/add',
            data: $('#product2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("product created");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function productDelete(id, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog('close');
                    productDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'settings/products/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    loadPage(page, true);
                    notify("product has been deleted");
                } else {
                    notify ("Oups, an error occured ! Please try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function productEdit(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'settings/products/edit/' + id,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            productEdit(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'settings/products/edit/' + id,
            data: $('#product2edit').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("product saved");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function licensesAdd(confirm) {
    if (!confirm) {
        $.ajax({
            url: 'settings/licensing/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    width : 400,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            licensesAdd(1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'settings/licensing/add',
            data: $('#licenses2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("licenses added");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function ogoneSave() {
    $.ajax({
        url: 'settings/ogone',
        data: $('#ogone2save').serialize(),
        type: 'POST',
        cache: false,
        success: function(data) {
            if (parseInt(data) > 0) {
                notify("Saved");
            } else {
                notify ("Oups, an error occured ! Please check your data and try again.");
            }
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

/* BEGIN OPPORTUNITY RELATED FUNCTIONS */
function opportunityCreateCompany(confirm){
    if (!confirm) {
        $('#errorcreatecompany').text('');
        $( "#createcompany" ).dialog({
            resizable: false,
            width:400,
            modal: true,
            buttons: {
                "Add this company": function() {
                    if ($('#createcompany > form > input').val() != '')
                        opportunityCreateCompany(true);
                    else
                        $('#errorcreatecompany').text('Name must not be empty');
                }
            }
        });
    }
    else {
        $.ajax({
            url: 'companies/add',
            data: $('#formcreatecompany').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (data != 'false') {
                    $("select[name='data[Lead][company_id]']").append($("<option SELECTED></option>").
                        attr("value",data).
                        text($('#createcompany > form > input').val()));
                    $('select.select').selectmenu('destroy');
                    $('select.select').selectmenu();
                    $( "#createcompany" ).dialog('close');
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function leadValidate() {
    if ( $('input[name="data[Lead][name]"]').val() == "" ) {
        $('input[name="data[Lead][name]"]').addClass('ui-state-error');
        return false;
    }
    $('input[name="data[Lead][name]"]').removeClass('ui-state-error');
    return true;
}

function opportunitySave(confirm){
    if (leadValidate()){
        if (!confirm) {
            $('#errormsg4history').text('');
            $( "#msg4history" ).dialog({
                resizable: false,
                width:400,
                modal: true,
                buttons: {
                    "Save": function() {
                        $(this).dialog('close');
                        opportunitySave(true);
                    }
                }
            });
        } else {
            $('#leadform > input:hidden').val($('#msg4history > form > input').val());
            $.ajax({
                url: 'leads/edit/' + id,
                data: $('#leadform').serialize(),
                type: 'POST',
                cache: false,
                success: function(data) {
                    if (data == id) {
                        formModified = false;
                        notify("Saved");
                    }else {
                        notify ("Oups, an error occured ! Please check your data and try again.");
                    }
                },
                error: function() {
                    notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                }
            });
            $('#msg4history > form > input').val("");
            $('#leadform > input:hidden').val("");
        }
    }
}

function opportunityDelete(confirm){
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    opportunityDelete(true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'leads/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    $( "#confirmation" ).dialog('close');
                    history.back();
                    notify("Your opportunity has been deleted");
                } else {
                    notify ("Oups, an error occured ! Please try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityHistory(){
    $('#history').load('leads/history/' + id, function() {
        $( "#history" ).dialog({
            resizable: false,
            height:250,
            modal: true
        });
    });
}

function opportunityAddContact(user) {
    if (!user) {
        $( "#assigncontact" ).dialog({
            resizable: false,
            width:300,
            modal: true,
            buttons: {
                "Assign this contact": function() {
                    if ($("input:hidden[name='data[Contact][id]']").val() != "") {
                        $( this ).dialog('close');
                        opportunityAddContact($("input:hidden[name='data[Contact][id]']").val());
                    }
                }
            }
        });
    } else {
        $.ajax({
            url: 'leads/addcontact/' + id,
            data: 'data[Contact][contacts_id]=' + user,
            type: 'POST',
            cache: false,
            success: function(data) {
                if (data == id) {
                    notify("Saved");
                    opportunityShowDetails($('#contactsdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
        $("input:hidden[name='data[Contact][id]']").val("");
        $("#autocontact").val('');
    }
}

function opportunityShowDetails(divtoload, reload) {
    visible = $(divtoload).is(":visible");

    if (!visible || reload)
        $(divtoload).load('leads/' + $(divtoload).attr('id') + '/' + id, function() {
            if (!reload)
                changeVisibility(divtoload);
            stripme();
            $(".button").button();
        });
    else
        changeVisibility(divtoload);
}

function addDocument() {
    $.ajax({
        url: 'documents/add',
        cache: false,
        success: function(data) {
            $( "#attachdocument" ).html(data);
            $( "#attachdocument" ).dialog({
                resizable: false,
                width:400,
                modal: true
            });
        },
        error: function(data) {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function deleteDocument(doc, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    deleteDocument(doc, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'documents/delete/' + doc,
            type: 'GET',
            cache: false,
            success: function(data) {
                notify("Document deleted");
                if (page.indexOf("leads/view/") == 0)
                    opportunityShowDetails($('#documentsdetails'), true);
                else
                    loadPage(page, true);
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityCreateContact(user) {
    if (!user) {
        $.ajax({
            url: 'contacts/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    width:350,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            opportunityCreateContact(1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'contacts/add',
            data: $('#contact2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("Contact saved");
                    $.ajax({
                        url: 'leads/addcontact/' + id,
                        data: 'data[Contact][contacts_id]=' + data,
                        type: 'POST',
                        cache: false,
                        success: function(data) {
                            if (data == id) {
                                notify("Your new contact was successfully linked to this opportunity");
                                opportunityShowDetails($('#contactsdetails'), 1);
                            } else {
                                notify ("Oups, an error occured ! Please check your data and try again.");
                            }
                        },
                        error: function() {
                            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                        }
                    });
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityRemoveContact(user) {
    $.ajax({
        url: 'leads/removecontact/' + id,
        data: 'data[LeadsContact][id]=' + user,
        type: 'POST',
        cache: false,
        success: function(data) {
            if (data == id) {
                notify("Contact Removed");
                opportunityShowDetails($('#contactsdetails'), 1);
            } else {
                notify ("Oups, an error occured ! Please check your data and try again.");
            }
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function opportunityEditContact(user, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'contacts/edit/' + user,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    width:350,
                    modal: true,
                    buttons: {
                        Save: function() {
                            $( this ).dialog( "destroy" );
                            opportunityEditContact(user, 1);
                        },
                        Delete: function() {
                            $( this ).dialog( "destroy" );
                            contactDelete(user);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }                      
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'contacts/edit/' + user,
            data: $('#contact2edit').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == user) {
                    notify("Contact saved");
                    opportunityShowDetails($('#contactsdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function contactDelete(id, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $("#ajaxmodal").dialog("close");
                    contactDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            },
            close: function() {
                $( this ).dialog( "destroy" );
            }
        });
    } else {
        $.ajax({
            url: 'contacts/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    $( "#confirmation" ).dialog('close');
                    loadPage(page, true);
                    notify("contact has been deleted");
                } else {
                    notify ("Oups, an error occured ! Please try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityAddEvent(confirm) {
    if (!confirm) {
        $.ajax({
            url: 'events/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $('#event2add').append('<input type="hidden" name="data[EventsLead][leads_id]" value="' + id + '"/>');
                $( "#startdate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( "#enddate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    width:350,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            opportunityAddEvent(1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'events/add',
            data: $('#event2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("New event added");
                    opportunityShowDetails($('#notesdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityEditEvent(event, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'events/edit/' + event,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#startdate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( "#enddate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    width:350,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            opportunityEditEvent(event, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'events/edit/' + event,
            data: $('#event2edit').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == event) {
                    notify("Event saved");
                    opportunityShowDetails($('#notesdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityDeleteEvent(event, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    opportunityDeleteEvent(event, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'events/delete/' + event,
            type: 'GET',
            cache: false,
            success: function(data) {
                if (parseInt(data) == event) {
                    notify("Event deleted");
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#notesdetails'), 1);
                    else if (page.indexOf("dashboards/") == 0)
                        $('#tasks #' + event).remove();
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityDoneEvent(event, confirm) {
    if (! confirm) {
        $( "#markasdone" ).dialog({
            resizable: false,
            width:250,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    opportunityDoneEvent(event, true)
                },
                Cancel: function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'events/setdone/' + event,
            type: 'GET',
            cache: false,
            success: function(data) {
                if (parseInt(data) == event) {
                    if (page.indexOf("events/") == 0)
                        if ($('#tasks #' + event+ ' td[id!="actions"]').attr('style') == ""){
                            $('#tasks #' + event + ' button:first span').text("Mark as not done");
                            $('#tasks #' + event+ ' td[id!="actions"]').attr('style', 'text-decoration: line-through');
                            notify("Event mark as done");
                        } else {
                            $('#tasks #' + event + ' button:first span').text("Mark as done");
                            $('#tasks #' + event+ ' td[id!="actions"]').attr('style', '');
                            notify("Event mark as not done");
                        }
                    else if (page.indexOf("dashboards/") == 0) {
                        $('#tasks #' + event).remove();
                        notify("Event mark as done");
                    } else if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#notesdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function leadsRecalculate() {
    var total = 0;
    var forecast = 0;
    $('table > tbody > tr').each(function () {
        ltotal = parseInt($('td:eq(4)', this).text().substr(2));
        lforecast = parseInt($('td:eq(6)', this).text().substr(2))
        total = total + ltotal;
        forecast = forecast + lforecast;
    });
    $('table > tfoot > td:eq(1)').text("$ " + total);
    $('table > tfoot > td:eq(3)').text("$ " + forecast);
}

function licenseDetails(id) {
    $.ajax({
        url: 'licenses/view/' + id,
        cache: false,
        success: function(data) {
            $( "#ajaxmodal" ).html(data);
            $( "#ajaxmodal" ).dialog({
                resizable: false,
                modal: true,
                buttons: {
                    Close: function() {
                        $( this ).dialog( "destroy" );
                    }
                },
                close: function() {
                    $( this ).dialog( "destroy" );
                }
            });
        },
        error: function(data) {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function resetLicense(id, confirm) {
    if (! confirm) {
        $( "#licconfirmation" ).dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    resetLicense(id, true)
                },
                Cancel: function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'licenses/reset/' + id,
            type: 'GET',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("License reseted");
                    $('#activated').html('<img src="img/icons/cross.png" />');
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#licensesdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function revokeLicense(id, confirm) {
    if (! confirm) {
        $( "#licconfirmation" ).dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    revokeLicense(id, true)
                },
                Cancel: function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'licenses/revoke/' + id,
            type: 'GET',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("License revoked");
                    $('#serialkey').attr('style', 'text-decoration: line-through;');
                    $('#serial').attr('style', 'text-decoration: line-through;');
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#licensesdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function licenseHistory(id){
    $('#lichistory').load('licenses/history/' + id, function() {
        $( "#lichistory" ).dialog({
            resizable: false,
            modal: true
        });
    });
}

function licenseAssign(lead, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'licenses/assign/' + lead,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Assign": function() {
                            $( this ).dialog( "destroy" );
                            licenseAssign(lead, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'licenses/assign/' + lead,
            data: $('#lic2assign').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("License assigned");
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#licensesdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityMerge(from, to, confirm) {
    if (!confirm) {
        if (!from || !to){
            $.ajax({
                url: 'leads/merge',
                cache: false,
                success: function(data) {
                    $( "#ajaxmodal" ).html(data);
                    $( "#ajaxmodal" ).dialog({
                        resizable: false,
                        width: 600,
                        modal: true,
                        buttons: {
                            "Next": function() {
                                from = $('select[name="from"]').val();
                                to = $('select[name="to"]').val();
                                $( this ).dialog( "destroy" );
                                opportunityMerge(from, to, 0);
                            },
                            Cancel: function() {
                                $( this ).dialog( "destroy" );
                            }
                        },
                        close: function() {
                            $( this ).dialog( "destroy" );
                        }
                    });
                },
                error: function(data) {
                    notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                }
            });
        } else {
            $.ajax({
                url: 'leads/merge/' + from + '/' + to,
                cache: false,
                success: function(data) {
                    $( "#ajaxmodal" ).html(data);
                    $( "#ajaxmodal" ).dialog({
                        resizable: false,
                        width: 600,
                        modal: true,
                        buttons: {
                            "Save": function() {
                                $( this ).dialog( "destroy" );
                                opportunityMerge(from, to, 1);
                            },
                            Cancel: function() {
                                $( this ).dialog( "destroy" );
                            }
                        },
                        close: function() {
                            $( this ).dialog( "destroy" );
                        }
                    });
                },
                error: function(data) {
                    notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                }
            });
        }

    } else {
        $.ajax({
            url: 'leads/merge/' + from + '/' + to,
            data: $('#leadmerge2').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == to) {
                    notify("Leads merged");
                    loadPage('leads/view/' + data);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function opportunityMergeDetails(type, line, caller) {
    var from = $(type + '[name="data[From]' + line + '"]').val();
    $(type + '[name="data[To]' + line + '"]').val(from);
    $(caller).remove();
}


function offerCreate(lead, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'offers/add/',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    width:300,
                    buttons: {
                        "Continue": function() {
                            $( this ).dialog( "destroy" );
                            offerCreate(lead, 1)
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'offers/add/' + lead,
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    offerCreate1(parseInt(data));
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function offerCreate1(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'offers/add1/' + id ,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal > div" ).dialog({
                    resizable: false,
                    modal: true,
                    width:350,
                    buttons: {
                        "Continue": function() {
                            if (validate()) {
                                $( this ).dialog( "destroy" );
                                offerCreate1(id, 1);
                            }
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                            offerDelete(id,1);
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'offers/add1/' + id ,
            data: $('#offerstep1').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    offerCreate2(parseInt(data));
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function offerCreate2(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'offers/add2/' + id ,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal > div" ).dialog({
                    resizable: false,
                    modal: true,
                    zIndex: 1,
                    width: 775,
                    buttons: {
                        "Continue": function() {
                            $( this ).dialog( "destroy" );
                            offerCreate2(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                            offerDelete(id,1);
                        }
                    },
                    open: function() {
                        recalculate();
                        editSelect();
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'offers/add2/' + id ,
            data: form2xml(),
            contentType: 'text/xml',
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    $('#offerstep2').remove();
                    offerCreate3(parseInt(data));
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function offerCreate3(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'offers/add3/' + id ,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal > div" ).dialog({
                    resizable: false,
                    modal: true,
                    width:350,
                    buttons: {
                        "Finish": function() {
                            $( this ).dialog( "destroy" );
                            offerCreate3(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                            offerDelete(id,1);
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'offers/add3/' + id ,
            data: $('#offerstep3').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify ("Offer created");
                    $('#offerstep3').remove();
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#ordersdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function offerView(id) {
    $.ajax({
        url: 'offers/view/' + id ,
        cache: false,
        success: function(data) {
            $( "#ajaxmodal" ).html(data);
            $( "#ajaxmodal" ).dialog({
                resizable: false,
                modal: true,
                width: 750,
                buttons: {
                    "Close": function() {
                        $( this ).dialog( "destroy" );
                    }
                },
                close: function() {
                    $( this ).dialog( "destroy" );
                }
            });
        },
        error: function(data) {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function offerDelete(id, confirm){
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            width:300,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    offerDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'offers/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    notify ("Offer deleted");
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#ordersdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function leadDescription () {
    $( "#editor" ).dialog({
        resizable: false,
        width:700,
        height:600,
        modal: true,
        buttons: {
            "OK": function() {
                $('input[name="data[Lead][description]"]').val(Encoder.htmlEncode($('textarea[name="editor"]').val()));
                $('.opportunity_description').html($('textarea[name="editor"]').val());
                formModified = true;
                $('textarea[name="editor"]').val('<p>&nbsp;</p>');
                $( this ).dialog( "destroy" );
            },
            Cancel: function() {
                $('textarea[name="editor"]').val('<p>&nbsp;</p>');
                $( this ).dialog( "destroy" );
            }
        }
    });
    $('textarea[name="editor"]').val(Encoder.htmlDecode($('input[name="data[Lead][description]"]').val()));
}

var editorConfig = {
    toolbar:
    [
    ['Bold','Italic','Underline','Strike'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['NumberedList','BulletedList'],
    ['Outdent','Indent','Blockquote','CreateDiv'],
    ['TextColor','BGColor'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
    ['Format','Font','FontSize'],
    ],
    height: "340",
    width: "680"
};

/* END OPPORTUNITY RELATED FUNCTIONS */

function createLead() {
    $( "#progress" ).dialog({
        resizable: false,
        modal: true
    });
    $.ajax({
        url: 'leads/add',
        data: '',
        type: 'POST',
        cache: false,
        success: function(data) {
            $("#ajaxmodal").dialog("close");
            $("#progress").dialog("close");
            loadPage('leads/view/' + data);
        },
        error: function(data) {
            $("#progress").dialog("close");
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function companyAdd (confirm) {
    if (!confirm) {
        $.ajax({
            url: 'companies/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    width:350,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            companyAdd(true);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'companies/add',
            data: $('#company2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("Company created");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function companyEdit(id, confirm) {
    if (!id){
        id = $('[name="data[Lead][company_id]"]').val();
    }
    if (!confirm) {
        $.ajax({
            url: 'companies/edit/' + id,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    width:350,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            companyEdit(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'companies/edit/' + id,
            data: $('#company2edit').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("Company saved");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function companyLeads(id) {
    $.ajax({
        url: 'companies/getleads/' + id,
        cache: false,
        success: function(data) {
            $( "#ajaxmodal" ).html(data);
            $( "#ajaxmodal" ).dialog({
                resizable: false,
                width:350,
                modal: true,
                buttons: {
                    Close: function() {
                        $( this ).dialog( "destroy" );
                    }
                },
                close: function() {
                    $( this ).dialog( "destroy" );
                }
            });
        },
        error: function(data) {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function companyDelete(id, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    companyDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'companies/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    $( "#confirmation" ).dialog('close');
                    loadPage(page, true);
                    notify("company has been deleted");
                } else {
                    notify ("Oups, an error occured ! Please try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function goToLead(id) {
    $( "#ajaxmodal" ).dialog("close");
    loadPage("leads/view/" + id);
}

function contactLeads(id) {
    $.ajax({
        url: 'contacts/getleads/' + id,
        cache: false,
        success: function(data) {
            $( "#ajaxmodal" ).html(data);
            $( "#ajaxmodal" ).dialog({
                resizable: false,
                width:350,
                modal: true,
                buttons: {
                    Close: function() {
                        $( this ).dialog( "destroy" );
                    }
                },
                close: function() {
                    $( this ).dialog( "destroy" );
                }
            });
        },
        error: function(data) {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function contactRequests(id) {
    $.ajax({
        url: 'contacts/getrequests/' + id,
        cache: false,
        success: function(data) {
            $( "#ajaxmodal" ).html(data);
            $( "#ajaxmodal" ).dialog({
                resizable: false,
                width:350,
                modal: true,
                buttons: {
                    Close: function() {
                        $( this ).dialog( "destroy" );
                    }
                },
                close: function() {
                    $( this ).dialog( "destroy" );
                }
            });
        },
        error: function(data) {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function userEdit(id, confirm, edit) {
    if (!confirm) {
        if (edit)
            var url = "users/edit/" + id +"/edit";
        else
            var url = "users/edit/" + id;
        $.ajax({
            url: url,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Change Password": function() {
                            $( this ).dialog( "destroy" );
                            userPassword(id);
                        },
                        "Save": function() {
                            $( this ).dialog( "destroy" );
                            userEdit(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'users/edit/' + id,
            data: $('#user2edit').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("User saved");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function userPassword(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'users/password/',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Save": function() {
                            $( this ).dialog( "destroy" );
                            userPassword(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'users/edit/' + id,
            data: $('#user2pwd').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("Password changed");
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function generateUserPassword() {
    $('input[name="data[User][password]"]').val(password(8));
}

function password(length, special) {
    var iteration = 0;
    var password = "";
    var randomNumber;
    if(special == undefined){
        var special = false;
    }
    while(iteration < length){
        randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
        if(!special){
            if ((randomNumber >=33) && (randomNumber <=47)) {
                continue;
            }
            if ((randomNumber >=58) && (randomNumber <=64)) {
                continue;
            }
            if ((randomNumber >=91) && (randomNumber <=96)) {
                continue;
            }
            if ((randomNumber >=123) && (randomNumber <=126)) {
                continue;
            }
        }
        iteration++;
        password += String.fromCharCode(randomNumber);
    }
    return password;
}

function checkVat(numvat) {
    $('#verify').attr('src', 'img/icons/loader.gif');
    $.ajax({
        url: 'companies/checkvat/' + numvat + '.xml',
        type: 'POST',
        cache: false,
        success: function(data) {
            if ($('valid', data).text() == 1)
                $('#verify').attr('src', 'img/icons/success.png');
            else
                $('#verify').attr('src', 'img/icons/error.png');
        },
        error: function() {
            $('#verify').attr('src', 'img/icons/error.png');
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

/*START REQUEST RELATED FUNCTIONS*/
var idRequest = -1;
function requestAccept(requestID) {
    $('#requestsaccept').dialog({
        resizable: false,
        width:400,
        modal: true,
        buttons: {
            "Accept": function() {
                $.get('requests/accept/' + requestID, function () {loadPage(page, true);});
                $( this ).dialog( "destroy" );
            },
            "Decline": function() {
                $.get('requests/refuse/' + requestID, function () {loadPage(page, true);});
                $( this ).dialog( "destroy" );
                $( "#ajaxmodal" ).dialog("destroy");                
            }
        }
    });
}

function requestAssign(requestID, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'requests/assign',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    width:400,
                    modal: true,
                    buttons: {
                        "Assign this request": function() {
                            $( this ).dialog( "destroy" );
                            requestAssign(requestID,true);
                        },
                        "Cancel": function() {
                            $( this ).dialog( "destroy" );
                        }
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $( "#progress" ).dialog({
            resizable: false,
            modal: true
        });
        $.ajax({
            url: 'requests/edit/' + requestID,
            data: $('#requestassign > form').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                $("#requests #" + requestID).remove();
                $("#progress").dialog("close");
                notify ("Request successfully assigned");
            },
            error: function(data) {
                $("#progress").dialog("close");
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function requestDelete(requestID, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    requestDelete(requestID,true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'requests/delete/' + requestID,
            cache: false,
            success: function(data) {
                $("#requests #" + parseInt(data)).remove();
                $("#ajaxmodal").dialog("close");
                notify ("The request has been deleted");
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });

    }
}

function requestDetails(requestID, readonly) {
    idRequest = requestID;
    var url;
    if (readonly)
        url = 'requests/view/' + idRequest + '/' + true;
    else
        url = 'requests/view/' + idRequest;

    $.ajax({
        url: url,
        cache: false,
        success: function(data) {
            $( "#ajaxmodal" ).html(data);
            if (page.toLowerCase().indexOf("leads/view/") > -1)
                $('#requestOpportunity').hide();
            $( "#ajaxmodal" ).dialog({
                resizable: false,
                width:700,
                modal: true,
                close: function() {
                    $( this ).dialog( "destroy" );
                }
            });
        },
        error: function(data) {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function requestUpdateStatus(requestID, newStatus) {
    $( "#progress" ).dialog({
        resizable: false,
        modal: true
    });
    $.ajax({
        url: 'requests/edit/' + requestID,
        data: 'data[Request][status]=' + newStatus,
        type: 'POST',
        cache: false,
        success: function(data) {
            data = parseInt(data);
            if (newStatus != "Open" && page.indexOf("requests/newrequests") == 0)
                $("#requests #" + data).remove();
            else if (page.indexOf("requests") == 0) {
                if (newStatus != "Open")
                    $("#requests #" + data).removeAttr('style');
                else if (newStatus == "Open")
                    $("#requests #" + data).attr('style', 'font-weight: bold;');
            }
            $("#progress").dialog("close");
            $("#ajaxmodal").dialog("close");
            notify ("The request has been updated");
        },
        error: function(data) {
            $("#progress").dialog("close");
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function requestCreateOpportunity(requestID, contactID) {
    $( "#progress" ).dialog({
        resizable: false,
        modal: true
    });
    $.ajax({
        url: 'leads/add',
        data: 'data[LeadsRequest][requests_id]=' + requestID + '&data[LeadsContact][contacts_id]=' + contactID,
        type: 'POST',
        cache: false,
        success: function(data) {
            $("#ajaxmodal").dialog("close");
            $("#progress").dialog("close");
            loadPage('leads/view/' + data);
        },
        error: function(data) {
            $("#progress").dialog("close");
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function requestLink2Opportunity(leadID) {
    $( "#progress" ).dialog({
        resizable: false,
        modal: true
    });
    $.ajax({
        url: 'leads/addRequest/' + $(leadID).val(),
        data: 'data[LeadsRequest][requests_id]=' + idRequest,
        type: 'POST',
        cache: false,
        success: function(data) {
            $("#ajaxmodal").dialog("close");
            $("#progress").dialog("close");
            loadPage('leads/view/' + data);
        },
        error: function(data) {
            $("#progress").dialog("close");
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function requestAddEvent(confirm) {
    if (!confirm) {
        $.ajax({
            url: 'events/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $('#event2add').append('<input type="hidden" name="data[EventsRequest][requests_id]" value="' + idRequest + '"/>');
                $( "#startdate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( "#enddate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    width:350,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            requestAddEvent(1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'events/add',
            data: $('#event2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("New event added");
                    requestDetails(idRequest);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function requestShowLead(id) {
    $("#ajaxmodal").dialog("close");
    loadPage("leads/view/" + id);
}
/* END REQUEST RELATED FUNCTIONS*/

function viewEmail(emailID) {
    $.ajax({
        url: 'emails/view/' + emailID,
        cache: false,
        success: function(data) {
            $( "#ajaxmodal" ).html(data);
            $( "#ajaxmodal" ).dialog({
                resizable: false,
                width:700,
                modal: true,
                close: function() {
                    $( this ).dialog( "destroy" );
                }
            });
        },
        error: function(data) {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}


function emailDelete(id, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    emailDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'emails/delete/' + id,
            type: 'GET',
            cache: false,
            success: function(data) {
                notify("Emails deleted");
                if (page.indexOf("leads/view/") == 0)
                    opportunityShowDetails($('#emailsdetails'), 1);
                else
                    loadPage(page, true);
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function sendMail(lead, to, subject) {
    if (!subject) {
        if (!lead)
            subject = "";
        else
            subject = "REF/" + lead;
    } else {
        subject = subject + " -- REF/" + lead;
    }

    if (!to)
        to = "";
    url = "https://mail.google.com/a/planningforce.com/mail/?view=cm&fs=1&tf=1&to=" + to + "&su=" + subject + "&bcc=administrator%40planningforce.com";
    window.open(url);
}

function email2lead(email, lead){
    $.ajax({
        url: 'emails/link2lead/' + email + '/' + lead,
        type: 'GET',
        cache: false,
        success: function(data) {
            notify("Email linked to Lead");
            var ref = "<span class=\"darkgreylabel\"style=\"padding-right:25px;\">Lead:</span>";
            ref = ref + "<a href=\"#\" onclick=\"requestShowLead(" + lead + ");\">" + data + "</a>";
            $('#leadselect').html(ref);
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function emailFetch(){
    $.ajax({
        url: 'emails/fetch',
        type: 'GET',
        cache: false,
        success: function(data) {
            loadPage(page, true);
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function cleanBounces() {
    $.ajax({
        url: 'settings/clean/bounces',
        data: $('#bounces').serialize(),
        type: 'POST',
        cache: false,
        success: function(data) {
            notify(data);
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function cleanNewsletter() {
    $.ajax({
        url: 'settings/clean/newsletter',
        data: $('#newsletter').serialize(),
        type: 'POST',
        cache: false,
        success: function(data) {
            notify(data);
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function cleanCommunity() {
    $.ajax({
        url: 'settings/clean/community',
        data: $('#community').serialize(),
        type: 'POST',
        cache: false,
        success: function(data) {
            notify(data);
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function setActiveSubmenu (submenu) {
    $('#submenu span').each(function() {
        $(this).removeClass("active");
    });
    $('#submenu #' + submenu).addClass("active");
}

function pluginAdd(confirm) {
    if (!confirm) {
        $.ajax({
            url: 'settings/pluginstore/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Create": function() {
                            $( this ).dialog( "destroy" );
                            pluginAdd(true);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'settings/pluginstore/add',
            data: $('#plugin2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("plugin added");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function pluginDelete(id, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog('close');
                    pluginDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'settings/pluginstore/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    loadPage(page, true);
                    notify("plugin has been deleted");
                } else {
                    notify ("Oups, an error occured ! Please try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function pluginEdit(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'settings/pluginstore/edit/' + id,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            pluginEdit(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'settings/pluginstore/edit/' + id,
            data: $('#plugin2edit').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("plugin saved");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function pluginAssign(lead, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'plugins/assign/' + lead,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Assign": function() {
                            $( this ).dialog( "destroy" );
                            pluginAssign(lead, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'plugins/assign/' + lead,
            data: $('#plugin2assign').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("Plugin assigned");
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#pluginsdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function pluginsReset(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'plugins/activation/' + id,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Update": function() {
                            $( this ).dialog( "destroy" );
                            pluginsReset(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'plugins/activation/' + id,
            data: $('#plugin2update').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("Plugin updated");
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#pluginsdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function repositoryAdd(confirm) {
    if (!confirm) {
        $.ajax({
            url: 'settings/repositories/add',
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Create": function() {
                            $( this ).dialog( "destroy" );
                            repositoryAdd(true);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'settings/repositories/add',
            data: $('#repository2add').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("repository added");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function repositoryDelete(id, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog('close');
                    repositoryDelete(id, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'settings/repositories/delete/' + id,
            cache: false,
            success: function(data) {
                if (data == id) {
                    loadPage(page, true);
                    notify("repository has been deleted");
                } else {
                    notify ("Oups, an error occured ! Please try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function repositoryEdit(id, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'settings/repositories/edit/' + id,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            repositoryEdit(id, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'settings/repositories/edit/' + id,
            data: $('#repository2edit').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) == id) {
                    notify("repository saved");
                    loadPage(page, true);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function themeSave() {
    $.ajax({
        url: 'settings/themes',
        data: $('#theme2save').serialize(),
        type: 'POST',
        cache: false,
        success: function(data) {
            if (parseInt(data) > 0) {
                notify("Saved");
            } else {
                notify ("Oups, an error occured ! Please check your data and try again.");
            }
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}
    
function documentSave() {
    $.ajax({
        url: 'settings/docsettings',
        data: $('#document2save').serialize(),
        type: 'POST',
        cache: false,
        success: function(data) {
            if (parseInt(data) > 0) {
                notify("Saved");
            } else {
                notify ("Oups, an error occured ! Please check your data and try again.");
            }
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function emailSave() {
    $.ajax({
        url: 'settings/mail',
        data: $('#email2save').serialize(),
        type: 'POST',
        cache: false,
        success: function(data) {
            if (parseInt(data) > 0) {
                notify("Saved");
            } else {
                notify ("Oups, an error occured ! Please check your data and try again.");
            }
        },
        error: function() {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    });
}

function offlineAssign(lead, confirm) {
    if (!confirm) {
        $.ajax({
            url: 'offlines/add/' + lead,
            cache: false,
            success: function(data) {
                $( "#ajaxmodal" ).html(data);
                $( "#ajaxmodal" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Assign": function() {
                            $( this ).dialog( "destroy" );
                            offlineAssign(lead, 1);
                        },
                        Cancel: function() {
                            $( this ).dialog( "destroy" );
                        }
                    },
                    close: function() {
                        $( this ).dialog( "destroy" );
                    }
                });
            },
            error: function(data) {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    } else {
        $.ajax({
            url: 'offlines/add/' + lead,
            data: $('#off2assign').serialize(),
            type: 'POST',
            cache: false,
            success: function(data) {
                if (parseInt(data) > 0) {
                    notify("License assigned");
                    if (page.indexOf("leads/view/") == 0)
                        opportunityShowDetails($('#offlinesdetails'), 1);
                } else {
                    notify ("Oups, an error occured ! Please check your data and try again.");
                }
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}

function deleteOffline(lic, confirm) {
    if (!confirm) {
        $( "#confirmation" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    deleteOffline(lic, true);
                },
                "No": function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    } else {
        $.ajax({
            url: 'offlines/delete/' + lic,
            type: 'GET',
            cache: false,
            success: function(data) {
                notify("Licence removed");
                if (page.indexOf("leads/view/") == 0)
                    opportunityShowDetails($('#offlinesdetails'), true);
                else
                    loadPage(page, true);
            },
            error: function() {
                notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
            }
        });
    }
}