<div id="adoc">
    <input id="fileInput2" name="fileInput2" type="file" /> <a href="javascript:$('#fileInput2').uploadifyUpload();">Upload Files</a> | <a href="javascript:$('#fileInput2').uploadifyClearQueue();">Clear Queue</a>
</div>
<script>
    if (page.indexOf("leads/view/") == -1)
        id = 0;
    $("#fileInput2").uploadify({
        'uploader'       : 'js/uploadify.swf',
        'script'         : 'documents/add/' + id,
        'cancelImg'      : 'img/icons/cross.png',
        'folder'         : 'documents',
        'multi'          : true,
        'wmode'          : 'transparent',
        'buttonImg'      : 'img/action2.png',
        'width'          : 183,
        onAllComplete: function() {
            $( "#attachdocument" ).dialog('close');
            if (id != 0 )
                opportunityShowDetails($('#documentsdetails'), true);
            else
                loadPage(page, true);
        }
    });
</script>