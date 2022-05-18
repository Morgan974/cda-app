<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase {

    public function testTestAreWorking() {
        $this->assertEquals(2, 1+1);
    }

    public function testUserEmailIsValid() {
        $user = new User();

        $user
            ->setEmail("test@test.com")
        ;

        $this->assertEquals("test@test.com", $user->getEmail());
    }

    public function testUserFirstnameIsValid() {
        $user = new User();

        $user
            ->setFirstname("Test")
        ;

        $this->assertEquals("Test", $user->getFirstname());
    }
}