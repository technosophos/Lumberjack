<?php

/* example.twig */
class __TwigTemplate_d7c2dae9fc740ff04fd21091683bb291 extends Twig_Template
{
    public function display(array $context)
    {
        // line 1
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
\t\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<!-- 
Twig is a PHP template engine. 
For a template reference, see 
http://www.twig-project.org/book/02-Twig-for-Template-Designers
-->
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
\t<title>";
        // line 11
        echo (isset($context['title']) ? $context['title'] : null);
        echo "</title>
\t
</head>

<body>
  <h1>";
        // line 16
        echo (isset($context['title']) ? $context['title'] : null);
        echo "</h1>
  <p>";
        // line 17
        echo (isset($context['welcome']) ? $context['welcome'] : null);
        echo "</p>
</body>
</html>
";
    }

}
