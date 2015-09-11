<?php

require_once "Project.php";
require_once "Contribution.php";

class ProjectTest extends PHPUnit_Framework_TestCase {
	
	protected $project;

	protected function setUp() {
    $this->project = new Project(220);
  }


  public function testScenario1() {
    $this->project->addContribution(new Contribution(70, 10, 1, "user1@mail.xyz"));
    $this->project->addContribution(new Contribution(100, 9.5, 1, "user2@mail.xyz"));

    $contributions =  $this->project->getContributions();

    $expected_owner_email = "user1@mail.xyz";
    $actual_owner_email = $contributions[1]->getOwnerEmail();
    $this->assertEquals($expected_owner_email, $actual_owner_email);

    $expected_owner_email = "user2@mail.xyz";
    $actual_owner_email = $contributions[0]->getOwnerEmail();
    $this->assertEquals($expected_owner_email, $actual_owner_email);

    $this->assertEquals(170, $this->project->getTotalAmount());
    $this->assertEquals(50, $this->project->getRemainingAmount());
    $this->assertEquals(85, $this->project->getAverageAmount());
    $this->assertEquals(9.75, $this->project->getAverageRate());
    $this->assertEquals(10, $this->project->getMaxRate());
  }

	public function testScenario2() {
    $this->project->addContribution(new Contribution(100, 10, 1, "user1@mail.xyz"));
    $this->project->addContribution(new Contribution(100, 10, 1, "user2@mail.xyz"));
    $this->project->addContribution(new Contribution(100, 9.7, 1, "user3@mail.xyz"));
    $this->project->addContribution(new Contribution(100, 8.5, 1, "user4@mail.xyz"));
    $this->project->addContribution(new Contribution(100, 9, 1, "user5@mail.xyz"));
    $this->project->addContribution(new Contribution(50, 9.5, 1, "user6@mail.xyz"));
    $this->project->addContribution(new Contribution(10, 9, 1, "user7@mail.xyz"));
    $this->project->addContribution(new Contribution(5, 9.5, 1, "user8@mail.xyz"));
    $this->project->addContribution(new Contribution(20, 9.4, 1, "user9@mail.xyz"));
    $this->project->addContribution(new Contribution(10, 9.4, 1, "user10@mail.xyz"));
    $this->project->addContribution(new Contribution(50, 8.9, 1, "user11@mail.xyz"));
    $this->project->addContribution(new Contribution(25, 8.9, 1, "user12@mail.xyz"));
    $this->project->addContribution(new Contribution(30, 8.6, 1, "user13@mail.xyz"));
    $this->project->addContribution(new Contribution(40, 8.7, 1, "user14@mail.xyz"));
    $this->project->addContribution(new Contribution(100, 8.4, 1, "user15@mail.xyz"));
    $this->project->addContribution(new Contribution(10, 8.5, 1, "user16@mail.xyz"));
    $this->project->addContribution(new Contribution(10, 8.5, 1, "user17@mail.xyz"));


    $this->assertEquals(220, $this->project->getTotalAmount());
    $this->assertEquals(0, $this->project->getRemainingAmount());
    $this->assertEquals(55, $this->project->getAverageAmount());
    $this->assertTrue(abs(8.475 - $this->project->getAverageRate()) < 0.01 );
    $this->assertEquals(8.6, $this->project->getMaxRate());
  }

}