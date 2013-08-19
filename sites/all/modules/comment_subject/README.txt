
Description
------------------
  Provides a default comment subject: 'Re: [parent comment/node title]'.
  Comment subject is derived from the node title, or the comment that the
  new comment is a reply to. If the parent already starts with 're:', it is
  not added a second time.

  Users can still edit the comment subject to their liking if comment 
  titles are enabled in the comment settings
  
  New features in 6.x-2.x branch:
  * Token support
    
    * more tokens are exposed for comments
      [comment:parent-title]  Parent's title (the parent might be the node or a another comment)
      [comment:numbering]     Auto-numbering with respect to node's comments count

Special treatment
-------------------
Some tokens that used to be provided by this module are now in core,
but some of them have a slightly different behavior.
Nevertheless this module preserves their previous behavior 
WHEN the comment has NOT a parent:
  * [comment:pid] evaluates to zero. ONLY when generating the subject.
  (other uses of this token still evaluate to empty string according to core's behavior)
  * [comment:parent:numbering] evaluates to zero.
  (even if used in other context, since it is owned by this module)
  * [comment:parent:numbering] doesn't requires subject to be disabled.
  (even when [comment:parent:*] appears marked with "**")    

IMPORTANT NOTES
-----------------

 * CAUTION: to achieve [comment:numbering] replacement works as expected previous existing comments shouldn't be deleted. 
   Otherwise numbering sequence won't be guaranted (i.e. existing comments won't get updated and new comments will count just existing ones)
   
 * Note that not every available pattern makes sense for comment's default subject.
   Replacements marked with "**" require comment subject field to be disabled, since they won't be available before submission.

Author
-----------------
Aldo Hoeben <aldo AT hoeben DOT net>

co-maintainers
-----------------
arhak      http://drupal.org/user/334209
sinasalek  http://drupal.org/user/52244
