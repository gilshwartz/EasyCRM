function FileActions (row, docManager){
    // Constructor
    var file = row;
    var parent = docManager;
    var id = $(file).attr('data-file');
    var isFolder = $(file).is(function() {
        return $(this).attr('data-type') === "dir"
    })    
    var logger = Log4js.getLogger("FileActions");
    var fileActions = null;
    var isSelected = false;
    
    logger.setLevel(logLevel);
    logger.addAppender(new Log4js.BrowserConsoleAppender());
    logger.debug("New FileActions : " + $(file).text());
    
    // Methods    
    this.open = function() {        
        if (isFolder) {
            parent.openFolder($('.filename a', file).text());
        } else {
            var url = "documents/view/" + id;
            window.open(url);
        }
    };
    
    // Methods    
    this.lead = function() {        
        loadPage("leads/view/" + $('.filelead', file).attr('lead-id'))
    };
    
    this.parent = function() {
        return parent;
    }
    
    this.lead = function(confirm) {
        if (!confirm) {
            $.ajax({
                url: 'documents/link2lead/' + id,
                cache: false,
                success: function(data) {
                    $( "#attachdocument" ).html(data);
                    $( "#attachdocument" ).dialog({
                        resizable: false,
                        width:400,
                        modal: true,
                        title: "Attache document to lead",
                        buttons: {
                            "Attach to Lead": function() {                                
                                fileActions.update(true);
                                $( this ).dialog( "destroy" );
                            },
                            Cancel: function() {
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
            $.ajax({
                url: 'documents/link2lead/' + id + '/' + $("input:hidden[name='data[LeadsDocument][leads_id]']").val(),
                type: 'GET',
                cache: false,
                success: function(data) {
                    if (data) {
                        notify("Document linked");
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
    };
    
    this.remove = function(confirm){
        if (!confirm) {
            $( "#confirmation" ).dialog({
                resizable: false,
                height:140,
                modal: true,
                buttons: {
                    "Yes": function() {
                        $( this ).dialog( "destroy" );
                        fileActions.remove(true);
                    },
                    "No": function() {
                        $( this ).dialog( "destroy" );
                    }
                }
            });
        } else {
            $.ajax({
                url: 'documents/delete/' + id,
                type: 'GET',
                cache: false,
                success: function(data) {
                    if (parseInt($(data).text()) > 0) {
                        notify("Document deleted");
                        loadPage(page, true);
                    } else {
                        notify ("Cannot delete file", "Error", "error");
                    }
                },
                error: function() {
                    notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                }
            });
        }
    };
    
    this.actions = function() {
        $('#contextualFile').display();
    }
    
    this.selected = function() {
        logger.debug("File is selected");
        if (! isSelected){
            $(file).addClass("selected");
            $('.selection input', file).attr('checked', true);
            isSelected = true;
        } else {
            $(file).removeClass("selected");
            $('.selection input', file).attr('checked', false);
            isSelected = false;
        }
    }
    
    this.isSelected = function() {
        return isSelected;
    }
    
    this.move = function(confirm, dest_id) {
        if (!confirm) {
            if (!fileActions.isSelected())
                fileActions.selected();
            $.ajax({
                url: 'documents/treeview/' + fileActions.parent().category(),
                cache: false,
                success: function(data) {
                    $( "#attachdocument" ).html(data);
                    $( "#attachdocument" ).dialog({
                        resizable: false,
                        width:400,
                        modal: true,
                        title: "Move file to ?",
                        buttons: {
                            "Move File": function() {
                                var folder = $('.my-tree.selected').closest('li').attr('folder-id');
                                if (folder != "" && parseInt(folder) > 0) {
                                    fileActions.move(true, folder);
                                    $( this ).dialog( "destroy" );
                                }
                            },
                            Cancel: function() {
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
            var files_to_move = parent.selectedFiles();
            for (var i in files_to_move) {
                var source_id = $(files_to_move[i]).closest('tr').attr('data-file');
                var source_name = $('.filename a', $(files_to_move[i]).closest('tr')).text();
                $.ajax({
                    url: 'documents/move/folder/' + source_id + "/" + dest_id,
                    type: 'GET',
                    cache: false,
                    success: function(data) {
                        if (parseInt($(data).text()) == 0) {
                            notify ("Cannot move file : " + source_name , "Error", "error");
                        }
                    },
                    error: function() {
                        notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                    },
                    async: false
                });
            }
            loadPage(page, true);
        }
    }
    
    fileActions = this;
    $(file).contextMenu('contextualFile', {
        onShowMenu: function(e, menu) {
            if (isFolder) {
                $('#update', menu).remove();
            }
            return menu;
        },
        bindings: {
            'open': function() {
                fileActions.open();
            },
            'lead': function() {
                fileActions.lead();
            },
            'move': function() {
                fileActions.move();
            },
            'delete': function() {
                fileActions.remove();
            }
        }
    });
    
    $( file ).draggable();
    
    $('.selection input', file).bind('click', function() {
        fileActions.selected();
    });
    
    $('.filename a', file).bind('click', function(evt) {
        fileActions.open(evt);
    });
    
    $('.filelead a', file).bind('click', function(evt) {
        fileActions.lead(evt);
    });
    
    $('.fileaction', file).bind('click', function() {
        $(file).contextMenu();
    });
    
    if (!isFolder){
        $(".draggable", file).draggable({
            helper: function(event){
                if (!fileActions.isSelected())
                    fileActions.selected();
                var items_to_move = $('#fileList tr[data-type!="dir"] input:checkbox:checked').toArray();
                return $('<div class="drag-item"></div>')
                .append('Moving ' + items_to_move.length + ' item(s)').appendTo('body');
            },
            cursorAt: {
                left: -5,
                top: -5
            },
            cursor: "url(https://mail.google.com/mail/images/2/closedhand.cur), default",
            distance: 10,
            delay: 100,
            revert: 'invalid',
            stop: function() { 
            //fileActions.selected();
            }
        });
    } else {
        $(file).droppable({
            drop: function( event, ui ) {
                var files_to_move = parent.selectedFiles();
                for (var i in files_to_move) {
                    var source_id = $(files_to_move[i]).closest('tr').attr('data-file');
                    var source_name = $('.filename a', $(files_to_move[i]).closest('tr')).text();
                    var dest_id = $(file).closest('tr').attr('data-file');
                    $.ajax({
                        url: 'documents/move/folder/' + source_id + "/" + dest_id,
                        type: 'GET',
                        cache: false,
                        success: function(data) {
                            if (parseInt($(data).text()) == 0) {
                                notify ("Cannot move file : " + source_name , "Error", "error");
                            }
                        },
                        error: function() {
                            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                        },
                        async: false
                    });
                }
                loadPage(page, true);
            }
        });
    }
    
}