<?php

namespace DevLucid;

class JsonResponse
{
    private static $instance;

    public function __construct()
    {
        $this->clear();
        $this->logger = null;
    }

    public static function get_instance($config=[])
    {
        if (null !== static::$instance) {
             return static::$instance;
        }
        ob_start();
        static::$instance = new JsonResponse();
        foreach ($config as $key=>$value)
        {
            static::$instance->$key = $value;
        }

        return static::$instance;
    }

    public function title($title)
    {
        $this->json['title'] = $title;
        $this->replace('#title',$title);
    }

    public function description($description)
    {
        $this->json['description'] = $description;
    }

    public function keywords($keywords)
    {
        $this->json['keywords'] = $keywords;
    }

    public function javascript($javascript)
    {
        $this->json['javascript'] .= $javascript;
    }

    public function prepend($selector, $content=null)
    {
        if (!isset($this->json['prepend'][$selector]))
        {
            $this->json['prepend'][$selector] = '';
        }
        
        $content = self::ob_content($content);
        $this->json['prepend'][$selector] .= (string) $content;
    }

    public function append($selector, $content=null)
    {
        if (!isset($this->json['append'][$selector]))
        {
            $this->json['append'][$selector] = '';
        }

        $content = self::ob_content($content);
        $this->json['append'][$selector] .= (string) $content;
    }

    public function replace($selector, $content=null)
    {
        if (!isset($this->json['replace'][$selector]))
        {
            $this->json['replace'][$selector] = '';
        }

        $content = self::ob_content($content);
        $this->json['replace'][$selector] .= (string) $content;
    }

    public static function ob_content($content=null)
    {
        if(is_null($content))
        {
            $content = ob_get_clean();
            ob_start();
        }
        return $content;
    }

    public function clear()
    {
        $this->json = [
            'title'       => null,
            'description' => null,
            'keywords'    => null,
            'javascript'  => '',
            'prepend'     => [],
            'append'      => [],
            'replace'     => [],
        ];
    }

    public function send()
    {
        header('Content-Type: application/json');
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate"); 
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache"); // HTTP/1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
        echo(json_encode(array_filter($this->json)));
        exit();
    }
}
