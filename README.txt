INTRODUCTION
------------

This module makes the iFrame Resizer javascript library available to your Drupal
site. With it enabled, you can "keep same and cross domain iFrames sized to
their content with support for window/content resizing, in page links, nesting
and multiple iFrames."

See the library's homepage for information on its capabilities:
http://davidjbradshaw.github.io/iframe-resizer/

 * For a full description of the module, visit the project page:
   https://www.drupal.org/sandbox/milodesc/2610780

 * To submit bug reports and feature suggestions, or to track changes:
   https://www.drupal.org/project/issues/2610780


REQUIREMENTS
------------

This module requires the iFrame Resizer javascript library to be installed in
Drupal's 'sites/all/libraries' directory.



INSTALLATION
------------

 * Download the library from https://github.com/davidjbradshaw/iframe-resizer

 * Unpack and rename the resulting directory "iframe_resizer" and place it in
   your Drupal installation's "sites/all/libraries" directory. Make sure the
   path to the library's main file becomes:
   "sites/all/libraries/iframe_resizer/js/iframeResizer.min.js".

 * Install as you would normally install a contributed Drupal module. See:
   https://drupal.org/documentation/install/modules-themes/modules-7
   for further information.


CONFIGURATION
-------------

 * Configure user permissions in Administration » People » Permissions:

   - Administer the iFrame Resizer module

     Users in roles with the "Administer the iFrame Resizer module"
     permission will see be able to configure the behavior of the iFrame
     Resizer module

 * Customize the module settings in Administration » Configuration » User
   interface » iFrame Resizer

   - The iFrame Resizer Usage fieldset

     At least one of the checkboxes in this fieldset should be checked,
     otherwise the module won't do anything.

   - The Advanced Options for Hosting Resizable iFrames fieldset

     This fieldset will appear if the 'This site will host resizable
     iFrames.' checkbox is checked. It will allow you to override some of
     the library's default behavior.


MAINTAINERS
-----------

Current maintainers:
 * Patrick Jensen (milodesc) - https://www.drupal.org/user/1498524