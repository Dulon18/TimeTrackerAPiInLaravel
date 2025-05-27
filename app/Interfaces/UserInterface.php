<?php

namespace App\Interfaces;

interface UserInterface
{
    public function register(array $data);
    public function login(array $users);
}
