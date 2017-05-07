<?php

namespace App\Helpers;

class SSocket {

    private $_socket;

    public function Request($args){
        $this->Create();
        $this->Send($args);
        return $this->Recv();
    }

    private function Create(){
        $this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if($this->_socket === false){
            $err = "socket_create() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error()));
            throw new \Exception($err);
        }

        $success = @socket_connect($this->_socket, "127.0.0.1", 9999);
        if(!$success){
            $err = "socket_connect() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error()));
            throw new \Exception($err);
        }
    }

    private function Send($args){
        $segs = [];
        foreach($args as $k=>$v)
            array_push($segs, $k."=".$v);

        $msg = implode("&", $segs);

        socket_write($this->_socket, $msg, strlen($msg));
    }

    private function Recv(){
        $buf = socket_read($this->_socket, 1024);
        if($buf === false){
            $err = "socket_read() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error())) . "\n";
            throw new \Exception($err);
        }
        return $buf;
    }

    private function Close(){
        socket_close($this->_socket);
    }

    public function __destruct(){
        $this->Close();
    }
    
}