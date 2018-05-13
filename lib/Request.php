<?php

namespace lib;

class Request
{
    private $post;
    private $get;
    private $server;
    private $files;

    /**
     * Request constructor.
     *
     * @param array $get
     * @param array $post
     * @param array $files
     * @param array $server
     *
     * @throws \Exception
     */
    public function __construct(array $get = [], array $post = [], array $files = [], array $server = [])
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;

        if (count($files) == 1) {
            $files = current($files);
            $upload_files = [];
            if (!empty($files['name'][0])) {
                foreach ($files as $attr => $values) {
                    foreach ($values as $key => $value) {
                        $upload_files[$key][$attr] = $value;
                    }
                }
            }

            foreach ($upload_files as $file) {
                $this->files[] = new UploadedFile($file);
            }
        }
    }

    /**
     * @return array
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $key
     * @param string|null $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (isset($this->get[$key])) {
            return $this->get[$key];
        }

        return $default;
    }

    /**
     * @param string $key
     * @param string|null $default
     *
     * @return mixed|null
     */
    public function post($key, $default = null)
    {
        if (isset($this->post[$key])) {
            return $this->post[$key];
        }

        return $default;
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return (bool) $this->post;
    }

    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function server($key, $default = null)
    {
        if (isset($this->server[$key])) {
            return $this->server[$key];
        }

        return $default;
    }

//    public function getUri()
//    {
//        $uri = $this->server('REQUEST_URI');
//        $uri = explode('?', $uri);
//
//        return $uri[0];
//    }
//    public function mergeGetWithArray(array $arr)
//    {
//        $_GET += $arr;
//        $this->get += $arr;
//    }
}