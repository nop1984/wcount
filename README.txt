CONTENTS OF THIS FILE
---------------------
 * End-user description
 * Developer description

 END-USER DESCRIPTION
----------------------
Word Count module provides word counting widget on node view page.
After installation you will have widget placed at "Manage Display" page of node type admin/structure/types/manage/<node_type>/display
Word counter value is updated or set only when you save the node. So nodes you had before installation of module will not display the word count.
To make them show word count you must resave each desired node manually via node edit tab.
Situation will repeat if you suspend the module in its settings form by link admin/config/wcount

 DEVELOPER DESCRIPTION
----------------------
Word counter setting form has next params (admin/config/wcount)
* Body field name in Node - ususally it is 'body'
* Counter Field Name - how name of counter field will be named, 'words_count' by default
* Use Drupal field or plain SQL table with virtual Drupal field - determines how counter value is stored. Either via BaseFieldDefinition either in own SQL table.
* Suspend module work - if suspended word counting will be stopped on node save.

'SQL table with virtual Drupal field' method does not require node save to update counter value (as values are added into seperate table), unlike 'Use Drupal field'. 
However it replaces $node->{Counter Field Name} in wcount_node_load() by results from own SQL table.
Own SQL table supports multilanguage of node, but does not support revisions.

Suspending module will stop updating nodes word count value. After turning module on again you will have manually update counts.
See wcount_node_presave() for 'Drupal field' method and wcount_node_saving() for 'SQL table with virtual Drupal field' method.
Suspending also will replace $node->{Counter Field Name} in wcount_node_load() to NULL

To do list:
- implement recalc button on settings page to fill in nodes with missing or outdated word count
- try to change BaseFieldDefinition of 'Body field name in Node' to setCalculated(TRUE) if 'SQL table with virtual Drupal field' is chosen 
  instead of replacing $node->{Counter Field Name} in wcount_node_load() (see CounterSQL.php)
- review all for optimization