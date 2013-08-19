<?php

/**
 * @file
 * helper functions for taxonomy_subterms
 */

/**
 * Get subterms tid and call the original taxonomy/term/% callback.
 *
 * @param $tid
 *   The TID of the term to call a callback for.
 * @param $callback
 *   The name of the original callback function.
 * @param $file
 *   The file the original term callback is in.
 * @param $filepath
 *   The path to $file.
 * @param ...
 *   Additional arguments to pass on to the callback.
 *
 * @ return
 *   The return value of the original callback function.
 */
 
 
 
function taxonomy_subterms_term_page($term, $callback, $file, $filepath) {

    if (is_file($filepath .'/'. $file)) {
        require_once($filepath .'/'. $file);
    }
    $arguments = array_slice(func_get_args(), 4);
    $map=FALSE;

    //$term is the taxonomy term object already.... 

    $vocab=taxonomy_vocabulary_load($term->vid);

    $depth=$term->taxonomy_subterms;
    if ($depth==TAXONOMY_SUBTERMS_INHERIT) {
        $depth=$vocab->taxonomy_subterms;
    }
    if ($depth==TAXONOMY_SUBTERMS_INFINITY) {
        $depth=NULL;
    }
    if ($depth==TAXONOMY_SUBTERMS_DISABLE) {
        $depth=0;
    }
    
    $map=$term->taxonomy_subterms_map;
    if ($map==TAXONOMY_SUBTERMS_INHERIT) {
       $map=$vocab->taxonomy_subterms_map;
    }
    
     // call "normal" callback
    $output = call_user_func_array($callback, $arguments);

    //assign a weight....  by default = nothing
    if (!empty($output["no_content"])) {
        $output["no_content"]["#weight"] = 3;    // no default weight in taxonomy module....
    }
    if (!empty($output["nodes"])) {
        $output["nodes"]["#weight"] = 3;    // no default weight in taxonomy module....
    }
    
    if ($map) {
        $map = taxonomy_get_tree($term->vid, $term->tid, $depth);

        $termsmap_output=theme('taxonomy_subterms_termsmap', $map);
        $output["subterms"] = $termsmap_output;
        
        // default weight = 1, i.e before.  If not, move down!
        if (variable_get('taxonomy_subterms_subterms_map_position', 'before') != 'before') {
          $output["subterms"]["#weight"] = 4;
        }
    }
    
    return $output;
}

        
        
function theme_taxonomy_subterms_termsmap($terms)
{
  drupal_add_css(drupal_get_path('module', 'taxonomy_subterms') . '/taxonomy_subterms.css');
  $add_descriptions = variable_get('taxonomy_subterms_subterms_map_description', 'no')=='yes';
        
  $output = array();
  foreach ($terms as $term)
  {
    if ($term->description && $add_descriptions) {
      $desc = array("#prefix" => "<span class='taxonomy-term-description'>", 
                        "#markup" => filter_xss_admin($term->description),
                        "#suffix" => '</span>');
    } else { 
      $desc = NULL;
    }
   
    $output[] = array("#prefix" => '<li class="taxonomy-term">',
                      "#markup" => l($term->name, "taxonomy/term/". ($term->tid) ),
                      "description" => $desc,
                      "#suffix" => '</li>');
  }
  return array("#prefix" => '<ul id="taxonomy-subterms-map">',
                "map" => $output,
                "#suffix" => '</ul>',
                "#weight" => 1);
}

function taxonomy_subterms_select_nodes($tid, $limit, $order = 'n.sticky DESC, n.created DESC') {
  $sql = 'SELECT DISTINCT(n.nid) FROM {node} n INNER JOIN {taxonomy_index} tn ON n.vid = tn.vid WHERE tn.tid = %d AND n.status = 1 ORDER BY '. $order;
  $sql = db_rewrite_sql($sql);
  $nid = db_result(db_query_range($sql, array($tid), 0, $limit));
  if ($nid) return node_load($nid);
  return NULL;
}
