This module integrates the great HTML5 front-end editor Aloha Editor to Drupal.
With Aloha Editor you can quickly edit the content on the front-end without the
need to go to edit section of node or block.

REQUIREMENTS
------------------
-Libraries module (http://drupal.org/project/libraries)
-Aloha Editor library (http://aloha-editor.org/)

INSTALL
------------------
-Download the aloha editor from http://www.aloha-editor.org/ and extract it
 into 'sites/all/libraries/aloha'.
-Download and install the Libraries module
-Install this module
-Configure the settings and activate some plugins!

TWEAK
------------------
Certain elements need to be removed from the content before being sent back to
the server to be saved. These include the aloha target spans themselves, any
elements added after the aloha target spans (eg context can add target links to
the content area) and contextual links (dependent upon your theme).
If you need to remove other elements you may override
Drupal.settings.alohaElementsToStrip
which is a simple jQuery selector string listing the elements to be removed.
It defaults to
Drupal.settings.alohaElementsToStrip = '.aloha-target, .aloha-target ~ *, .contextual-links-wrapper'
so remember to include these in your override.
You should be able to override this from within a module or from your theme js.

NOTICE
------------------
Because Aloha Editor is also under active development some of the plugins might
not work. So if you face a problem where the editor toolbar is not showing up
you just have to try to found out which of the plugins is causing the problem.

The aloha library includes a copy of ExtJS library. This version of the ExtJS
library overrides the Array prototype which may cause obscure bugs with existing
javascript in Drupal or Drupal modules. If you do experience unexplainable
javascript errors after enabling the module, try disabling the module to see if
the error is resolved and report the issue here if so.

The aloha library is in the process of being upgraded to use jQuery UI instead
of ExtJS so this should not be a problem with future versions of the library.
https://github.com/alohaeditor/Aloha-Editor/tree/dev-jqueryui
