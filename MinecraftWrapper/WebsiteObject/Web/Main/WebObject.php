<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Main;

class WebMainObjectNotImplement extends \Exception {
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = NULL) {
        return parent::__construct($message, $code, $previous);
    }
}

/**
 *
 * @author jimmy.provencher
 */
interface WebObject {
    public function generatecode();
    public function getHtmlstring();
}
