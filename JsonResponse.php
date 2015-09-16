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
        static::$instance = new JsonResponse();
        foreach ($config as $key=>$value)
        {
            static::$instance->$key = $value;
        }

        return static::$instance;
    }

    public function title($title)
    {
        $this->response['title'] = $title;
    }

    public function description($description)
    {
        $this->response['description'] = $description;
    }

    public function keywords($keywords)
    {
        $this->response['keywords'] = $keywords;
    }

    public function javascript($javascript)
    {
        $this->response['javascript'] .= $javascript;
    }

    public function prepend($selector, $content=null)
    {
        if (!isset($this->response['prepend'][$selector]))
        {
            $this->response['prepend'][$selector] = '';
        }
        $this->response['prepend'][$selector] .= (string) $content;
    }

    public function append($selector, $content=null)
    {
        if (!isset($this->response['append'][$selector]))
        {
            $this->response['append'][$selector] = '';
        }
        $this->response['append'][$selector] .= (string) $content;
    }

    public function replace($selector, $content=null)
    {
        if (!isset($this->response['replace'][$selector]))
        {
            $this->response['replace'][$selector] = '';
        }
        $this->response['replace'][$selector] .= (string) $content;
    }

    public function clear()
    {
        $this->response = [
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
        echo(json_encode(array_filter($this->response)));
        exit();
    }
}
