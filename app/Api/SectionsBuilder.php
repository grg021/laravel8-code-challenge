<?php


namespace App\Api;


interface SectionsBuilder
{

    public function initialize();
    public function build();
    public function transform($transformer);
    public function get();

}
