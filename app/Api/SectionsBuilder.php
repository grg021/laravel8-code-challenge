<?php


namespace App\Api;


use Illuminate\Support\Collection;

interface SectionsBuilder
{

    public function initialize(Collection $rankings);
    public function build();
    public function transform($transformer);
    public function get();

}
