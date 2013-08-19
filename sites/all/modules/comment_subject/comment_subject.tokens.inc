<?php

/**
 * @file
 * Builds placeholder replacement tokens for comment-related data.
 */

/**
 * Implements hook_token_info().
 */
function comment_subject_token_info() {
  // Comment tokens
  $comment['numbering'] = array(
    'name' => t("Comment numbering"),
    'description' => t("Auto-numbering with respect to node's comments count."),
  );
  $comment['parent-title'] = array(
    'name' => t("Paren's title"),
    'description' => t("The title of the parent comment if it is replying to another comment, otherwise the title of the node."),
  );
  
  return array(
    //'types' => array(),
    'tokens' => array(
      'comment' => $comment,
    ),
  );
}

/**
 * Implements hook_tokens().
 */
function comment_subject_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $sanitize = !empty($options['sanitize']);

  $replacements = array();

  if ($type == 'comment' && !empty($data['comment'])) {
    $comment = $data['comment'];
    // support uninitialized pid (comment_form will have it uninitialized for new comment)
    if (!isset($comment->pid)) $comment->pid = 0;
    
    foreach ($tokens as $name => $original) {
      switch ($name) {
        // main behavior aimed by this module
        case 'parent-title':
          $parent_tokens = array();
          foreach ($tokens as $token => $raw) {
            if ($token == 'parent-title') {
              // emulate either chained comment:parent:title or node:title
              $parent_tokens['title'] = $raw;                
            }
          }
          if ($parent = comment_load($comment->pid)) {
            $replacements += token_generate('comment', $parent_tokens, array('comment' => $parent), $options);
          }
          else {
            $node = node_load($comment->nid);
            $replacements += token_generate('node', $parent_tokens, array('node' => $node), $options);
          }
          break;
          
        // other tokens provided by this module
        
        case 'numbering':
          $replacements[$original] = _comment_subject_get_numbering($comment->cid);
          break;
        // [comment:parent:numbering] evaluates to zero when there is no parent (legacy behavior)
        // if the comment doesn't have a parent the chain will brake
        // catching $name == 'parent:numbering' since numbering can't be further chained
        // instead of token_find_with_prefix('numbering', token_find_with_prefix($tokens, 'parent'))
        case 'parent:numbering':
          if (empty($comment->pid)) {
            $replacements[$original] = 0;
          }
          break;
      }
    }
  }
  
  return $replacements;
}

function _comment_subject_get_numbering($cid) {
  static $comments_count_cache = array();
  // any token fetching this had to the comment loaded, and therefore catched already
  $comment = comment_load($cid);
  if (!isset($comments_count_cache[$comment->cid])) {
    $query = 'SELECT COUNT(cid) FROM {comment} WHERE nid = :nid AND cid < :cid ORDER BY cid ASC';
    $comments_count_cache[$comment->cid] = db_query($query, array(
      ':nid' => $comment->nid, 
      ':cid' => $comment->cid,
    ))->fetchField();
  }
  $comment_count = $comments_count_cache[$comment->cid];
  return ($comment_count + 1);
}
