TIMS: Twig Interface Management System

Drupal 7 module that enables the creation of Twig templates within the browser.

See INSTALL.txt for installation instructions.

-----------------------
Usage:
Manage templates at "Structure->Twig templates".

-----------------------
Naming your templates:
Naming convention is based on what is contained in the theme_get_suggestions array.
For example, if you would like to template a node of the article content type,
instead of creating a php file named "node--article.tpl.php" you will create a
template in the module's interface called "node__article". See
https://drupal.org/node/223440 for more about working with template suggestions
and their naming convention.

-----------------------
Twig syntax:
For a guide to the Twig syntax, refer to the documention for theming Drupal 8
(https://drupal.org/node/1906384) which uses Twig for theming as part of core.

-----------------------
Differences between Drupal 8 theming and this module:

Drupal 8 Twig syntax needs to modified when printing a field. The variable
needs to wrapped in the render function and the raw filter must be applied:

Drupal 7 TIMS Module: {{ render(content.body)|raw }}
Drupal 8: {{ content.body }}
