<?php

/* TwigBundle:Exception:exception_full.html.twig */
class __TwigTemplate_4fd858a3b11dad75c291461e7216b4078f622fbec35cb506df80151a8c78fce3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("TwigBundle::layout.html.twig");

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "TwigBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/framework/css/exception.css"), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
";
    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "message"), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["status_text"]) ? $context["status_text"] : $this->getContext($context, "status_text")), "html", null, true);
        echo ")
";
    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        // line 12
        echo "    ";
        $this->env->loadTemplate("TwigBundle:Exception:exception.html.twig")->display($context);
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:exception_full.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 12,  54 => 11,  43 => 8,  98 => 40,  93 => 9,  46 => 10,  44 => 9,  40 => 7,  32 => 6,  27 => 4,  22 => 1,  120 => 20,  117 => 19,  110 => 22,  108 => 19,  105 => 18,  102 => 17,  94 => 34,  90 => 32,  88 => 6,  84 => 29,  82 => 28,  78 => 40,  73 => 16,  64 => 13,  61 => 12,  56 => 11,  53 => 10,  47 => 8,  41 => 5,  33 => 4,  158 => 79,  139 => 63,  135 => 62,  131 => 61,  127 => 28,  123 => 59,  106 => 45,  101 => 43,  97 => 41,  85 => 32,  80 => 41,  76 => 17,  74 => 27,  63 => 19,  58 => 17,  48 => 9,  45 => 8,  42 => 7,  36 => 7,  30 => 3,);
    }
}
