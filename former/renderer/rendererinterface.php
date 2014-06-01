<?php
/**
 * Renderer interface, used for all kind of items rendering
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */


interface Former_Renderer_RendererInterface
{
    public function __construct($field, array $options);
    public function render();
}
