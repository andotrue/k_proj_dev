<?php

/* TwigBundle:Exception:logs.html.twig */
class __TwigTemplate_0b001b44c44ba50c666a0f93a2a23dd46c311c7deb60ee0dfcbedb5209edd7eb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<ol class=\"traces logs\">
    ";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["logs"]) ? $context["logs"] : $this->getContext($context, "logs")));
        foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
            // line 3
            echo "        <li";
            if (($this->getAttribute((isset($context["log"]) ? $context["log"] : $this->getContext($context, "log")), "priority") >= 400)) {
                echo " class=\"error\"";
            } elseif (($this->getAttribute((isset($context["log"]) ? $context["log"] : $this->getContext($context, "log")), "priority") >= 300)) {
                echo " class=\"warning\"";
            }
            echo ">
            ";
            // line 4
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["log"]) ? $context["log"] : $this->getContext($context, "log")), "priorityName"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["log"]) ? $context["log"] : $this->getContext($context, "log")), "message"), "html", null, true);
            echo "
        </li>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 7
        echo "</ol>
";
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:logs.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  35 => 4,  26 => 3,  87 => 20,  55 => 13,  31 => 5,  25 => 4,  21 => 2,  92 => 21,  89 => 20,  79 => 18,  75 => 17,  72 => 16,  68 => 14,  50 => 8,  24 => 3,  201 => 92,  199 => 91,  196 => 90,  187 => 84,  183 => 82,  173 => 74,  171 => 73,  168 => 72,  166 => 71,  163 => 70,  156 => 66,  151 => 63,  142 => 59,  138 => 57,  136 => 56,  133 => 55,  121 => 46,  115 => 43,  112 => 42,  91 => 31,  86 => 28,  69 => 25,  66 => 15,  62 => 23,  51 => 12,  49 => 19,  39 => 6,  19 => 1,  57 => 14,  54 => 21,  43 => 8,  98 => 40,  93 => 9,  46 => 7,  44 => 10,  40 => 7,  32 => 12,  27 => 4,  22 => 2,  120 => 20,  117 => 44,  110 => 22,  108 => 19,  105 => 40,  102 => 17,  94 => 22,  90 => 32,  88 => 6,  84 => 29,  82 => 28,  78 => 40,  73 => 16,  64 => 12,  61 => 12,  56 => 9,  53 => 10,  47 => 8,  41 => 9,  33 => 5,  158 => 67,  139 => 63,  135 => 62,  131 => 61,  127 => 28,  123 => 47,  106 => 45,  101 => 24,  97 => 41,  85 => 19,  80 => 19,  76 => 17,  74 => 27,  63 => 19,  58 => 17,  48 => 9,  45 => 8,  42 => 7,  36 => 7,  30 => 3,);
    }
}
