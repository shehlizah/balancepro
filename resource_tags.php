<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// SET HEADER
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
include('../db/database.php');

function getResourceTags($postId)
    {
        $sql = "select T.term_id from wp_terms as T left join wp_term_taxonomy as TT on T.term_id = TT.term_id left join wp_term_relationships as TR on TR.term_taxonomy_id = TT.term_taxonomy_id where TT.taxonomy = 'resource_tag' and TR.object_id = '{$postId}'";
        $query = $this->db->query($sql);
        $result = [];
        while ($row = $query->fetch_assoc()) {
            $result[] = (int)$row['term_id'];
        }
        return $result;
    }
?>