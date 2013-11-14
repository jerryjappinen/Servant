
# URL samples

Servant automatically manipulates URLs in CSS and LESS files (`url()`). This is done to make sure that they consistently point to the same place as expected on the site. This page gives you an idea of what's going on.

These are the basic rules:

- Absolute URLs are never touched.
- CSS/LESS: relative `url()` points to a file in the same directory as the stylesheet file.
- CSS/LESS: root-relative `url()` (starts with a `/`) points to a file in the **root** directory of your site's content **or** theme directory.
