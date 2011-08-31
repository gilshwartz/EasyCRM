<?php

/**
 * Explode any single-dimensional array into a full blown tree structure,
 * based on the delimiters found in it's keys.
 *
 * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @author  Lachlan Donald
 * @author  Takkie
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: explodeTree.inc.php 89 2008-09-05 20:52:48Z kevin $
 * @link    http://kevin.vanzonneveld.net/
 *
 * @param array   $array
 * @param string  $delimiter
 * @param boolean $baseval
 *
 * @return array
 */
function explodeTree($array, $delimiter = '_', $baseval = false) {
    if (!is_array($array))
        return false;
    $splitRE = '/' . preg_quote($delimiter, '/') . '/';
    $returnArr = array();
    foreach ($array as $key => $val) {
        // Get parent parts and the current leaf
        $parts = preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
        $leafPart = array_pop($parts);

        // Build parent structure
        // Might be slow for really deep and large structures
        $parentArr = &$returnArr;
        foreach ($parts as $part) {
            if (!isset($parentArr[$part])) {
                $parentArr[$part] = array();
            } elseif (!is_array($parentArr[$part])) {
                if ($baseval) {
                    $parentArr[$part] = array('__base_val' => $parentArr[$part]);
                } else {
                    $parentArr[$part] = array();
                }
            }
            $parentArr = &$parentArr[$part];
        }

        // Add the final part to the structure
        if (empty($parentArr[$leafPart])) {
            $parentArr[$leafPart] = $val;
        } elseif ($baseval && is_array($parentArr[$leafPart])) {
            $parentArr[$leafPart]['__base_val'] = $val;
        }
    }
    return $returnArr;
}

function liTree($arr, $mother_run=false) {
    if ($mother_run) {
        // the beginning of plotTree. We're at rootlevel
        echo '<ul id="browser" class="filetree">';
    }

    foreach ($arr as $k => $v) {
        $id = $v['id'];
        unset($v['id']);
        if (empty($v)) {
            $v = $k;
        }
        if (!is_array($v)) {
            echo '<li folder-id="' . $id . '"><span class="folder my-tree">&nbsp;&nbsp;' . $k . '</span></li>';
        } else {
            echo '<li folder-id="' . $id . '"><span class="folder my-tree">&nbsp;&nbsp;' . $k . '</span><ul>';
            liTree($v);
            echo '</ul></li>';
        }
    }

    if ($mother_run) {
        echo '</ul>';
    }
}

$tree = array();

foreach ($directories as $directory) {
    $name = $directory['Document']['path'] . $directory['Document']['name'];
    $tree[$name] = array('id' => $directory['Document']['id']);
    //$tree[$name] = $name;
}
?>
<form action="#" id="treeviewer">
    <?php
    liTree(explodeTree($tree, "/"), true);
    ?>
</form>
<script>
    $(document).ready(function(){
        $("#browser").treeview({
            collapsed: true, 
            persist: "location",
            animated: "fast"
        });
        $('.my-tree').mouseover(function() {
            $(this).addClass("mouseOver");
        });
        $('.my-tree').mouseout(function() {
            $(this).removeClass("mouseOver");
        });
        $('.my-tree').click(function() {
            setSelected(this);
        });
    });
    
    function setSelected(elmt) {
        $('.my-tree.selected').removeClass('selected');
        $(elmt).addClass('selected');
    }
    
    
</script>