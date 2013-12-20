<?php

/* TwigBundle:Exception:traces.txt.twig */
class __TwigTemplate_93f52f5f906c1f47e9c034c43fcc014323fdb908260fbb207624b1c1c591a9c0 extends Twig_Template
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
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "trace"))) {
            // line 2
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "trace"));
            foreach ($context['_seq'] as $context["_key"] => $context["trace"]) {
                // line 3
                $this->env->loadTemplate("TwigBundle:Exception:trace.txt.twig")->display(array("trace" => (isset($context["trace"]) ? $context["trace"] : $this->getContext($context, "trace"))));
                // line 4
                echo "
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trace'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:traces.txt.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 13,  35 => 4,  26 => 5,  87 => 20,  55 => 13,  31 => 5,  25 => 3,  21 => 2,  92 => 21,  89 => 20,  79 => 18,  75 => 17,  72 => 16,  68 => 14,  50 => 8,  24 => 4,  201 => 92,  199 => 91,  196 => 90,  187 => 84,  183 => 82,  173 => 74,  171 => 73,  168 => 72,  166 => 71,  163 => 70,  156 => 66,  151 => 63,  142 => 59,  138 => 57,  136 => 56,  133 => 55,  121 => 46,  115 => 43,  112 => 42,  91 => 31,  86 => 28,  69 => 25,  66 => 15,  62 => 23,  51 => 15,  49 => 19,  39 => 6,  19 => 1,  57 => 16,  54 => 21,  43 => 8,  98 => 40,  93 => 9,  46 => 7,  44 => 10,  40 => 7,  32 => 12,  27 => 4,  22 => 2,  120 => 20,  117 => 44,  110 => 22,  108 => 19,  105 => 40,  102 => 17,  94 => 22,  90 => 32,  88 => 6,  84 => 29,  82 => 28,  78 => 40,  73 => 16,  64 => 12,  61 => 12,  56 => 9,  53 => 10,  47 => 8,  41 => 9,  33 => 10,  158 => 67,  139 => 63,  135 => 62,  131 => 61,  127 => 28,  123 => 47,  106 => 45,  101 => 24,  97 => 41,  85 => 19,  80 => 19,  76 => 17,  74 => 27,  63 => 19,  58 => 17,  48 => 9,  45 => 8,  42 => 14,  36 => 7,  30 => 3,);
    }
}
