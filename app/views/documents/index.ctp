<div class="doc_header">
    <h1 class="datepicker">Documents & Templates</h1>
</div>
<div class="doc_view">
    <div id="doc_quick_nav"> 
        <ul> 
            <?php
            foreach ($categories as $category) {
                if (trim($category) != "")
                    if ($category == $current_category)
                        echo '<li><a class="active" style="background-image:url(img/documents/' . strtolower(trim($category)) . '.png);background-size: 16px;" href="documents/?category=' . trim($category) . '" >' . trim($category) . '</a></li>';
                    else
                        echo '<li><a style="background-image:url(img/documents/' . strtolower(trim($category)) . '.png);background-size: 16px;" href="documents/?category=' . trim($category) . '" >' . trim($category) . '</a></li>';
                else
                    echo '<li><separator/></li>';
            }
            ?>
        </ul> 
    </div>

    <div id="doc_content">
        <p class="nav"> 
            <?php
            $nav = "";

            foreach (explode('/', $current_path) as $key => $value) {
                if ($key == 0) {
                    echo '<a href="documents/?category=' . $current_category . '"><img src="img/documents/harddrive.png" width="16px" alt="Root" /></a>';
                } else {
                    $nav .= "/" . $value;
                    echo '<a href="documents/?category=' . $current_category . '&path=' . $nav . '">' . $value . '</a>';
                }
            }
            ?>
        </p>

        <p class="actions"> 
            <a href="#" onclick="docManager.addFolder();return false;" style="background-image:url(img/documents/folder_add.png);background-size: 16px;">Add Folder</a>
            <a href="#" onclick="docManager.addFile();return false;" style="background-image:url(img/documents/document_add.png);background-size: 16px;">Add Files</a>
            <a href="#" onclick="docManager.remove();return false;" style="background-image:url(img/documents/remove.png);background-size: 16px;">Delete</a>
        </p>
        <table class="doc_content" cellspacing="0">
            <thead>
                <tr>
                    <th></th>
                    <th><input type="checkbox" id="select_all" /></th>
                    <th>Name</th>
                    <th>Lead</th>
                    <th>Size</th>
                    <th>Modified</th>
                </tr>
            </thead>
            <tbody  id="fileList">
                <?php
                foreach ($documents as $doc) {
                    echo "<tr data-file='" . $doc['Document']['id'] . "' data-type='" . $doc['Document']['type'] . "' data-mime='" . $doc['Document']['mime'] . " id=\"draggable\" class=\"ui-widget-content\"'>";
                    echo '<td class="draggable"><img src="img/documents/cleardot.png" alt="" /></td>';
                    echo '<td class="selection"><input type="checkbox" /></td>';

                    $extension = pathinfo($doc['Document']['name']);
                    if ($doc['Document']['type'] == 'file') {
                        if (isset($extension['extension'])) {
                            echo '<td class="filename"><a style="background-image:url(img/documents/file_' . $extension['extension'] . '.png);background-size: 16px;" href="#" onclick="#">' . $doc['Document']['name'] . '</a></td>';
                        } else {
                            echo '<td class="filename"><a style="background-image:url(img/documents/document_blank.png);background-size: 16px;" href="#" onclick="#">' . $doc['Document']['name'] . '</a></td>';
                        }
                        if (isset($doc['Lead']['id']))
                            echo '<td class="filelead" lead-id="' . $doc['Lead']['id'] . '"><a href="#">' . $doc['Lead']['name'] . '</a></td>';
                        else
                            echo '<td class="filelead"></td>';
                        echo '<td class="filesize">' . $this->Document->humanFileSize($doc['Document']['size']) . '</td>';
                    } else {
                        echo '<td class="filename"><a style="background-image:url(img/documents/folder.png);background-size: 16px;" href="#" >' . $doc['Document']['name'] . '</a></td>';
                        echo '<td class="filelead"></td>';
                        echo '<td class="filesize"></td>';
                    }

                    echo '<td class="date">' . $doc['Document']['date'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php if ($this->Paginator->hasNext() || $this->Paginator->hasPrev()) { ?>
            <div class="paginator">
                <?php
                $url = array('category' => $current_category, 'path' => $current_path);
                $this->Paginator->options(array('url' => array('?' => $url)));
                ?>
                <!-- Shows the page numbers -->
                <?php echo $this->Paginator->numbers(); ?>
                <!-- Shows the next and previous links -->
                <?php echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled')); ?>
                <?php echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled')); ?>
                <!-- prints X of Y, where X is current page and Y is number of pages -->
                <?php echo $this->Paginator->counter(); ?>
            </div>
        <?php } ?>
    </div>
    <div class="contextMenu" id="contextualFile" style="display: none;">
        <ul>
            <li id="open"><img src="img/documents/open.png" /> Open</li>
            <li id="lead"><img src="img/documents/commercial16.png" /> Lead</li>
            <li id="move"><img src="img/documents/move.png" /> Move</li>
            <li id="delete"><img src="img/documents/cross.png" /> Delete</li>
        </ul>
    </div>
    <div class="contextMenu" id="contextualDocuments" style="display: none;">
        <ul>
            <li id="folder"><img src="img/documents/folder_add.png" width="16px"/> Add Folder</li>
            <li id="file"><img src="img/documents/document_add.png" width="16px"/> Add Files</li>            
        </ul>
    </div>
    
</div>

<script type="text/javascript">
    var docManager;
    $(function() {
              
        $('.dropArrow').live('click', function(event) {
            event.preventDefault();
            FileActions.display($(this).parent());
        });
                
        /* Cannot use $.bind() since jQuery does not normalize native events. */
        docManager = new DocumentManager($('.doc_view').get(0), "<?php echo $current_path; ?>", "<?php echo $current_category; ?>");
        
        $("#doc_quick_nav > ul > li").droppable({
            drop: function( event, ui ) {
                var files_to_move = docManager.selectedFiles();
                for (var i in files_to_move) {
                    var source_id = $(files_to_move[i]).closest('tr').attr('data-file');
                    var source_name = $('.filename a', $(files_to_move[i]).closest('tr')).text();
                    var dest_id = $('a', event.target).text();
                    $.ajax({
                        url: 'documents/move/category/' + source_id + "/" + dest_id,
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
    });  
    paginator();
    
</script>