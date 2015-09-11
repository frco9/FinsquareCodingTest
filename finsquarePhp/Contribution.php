<?php


class Contribution {
  private $id;
  private $kept;
  private $amount;
  private $rate;
  private $project_id;
  private $owner_email;
  private $timestamp;
  private $score;

  function __construct($amount, $rate, $project_id, $owner_email) {
    if (($amount < 1) || ($amount > 100) || ($rate > 10) || ($rate < 1)) {
      throw new InvalidArgumentException('Amount must be between 1 and 100');
    }

    $this->id = uniqid();
    $this->kept = true;
    $this->amount = $amount;
    $this->rate = $rate;
    $this->project_id = $project_id; 
    $this->owner_email = $owner_email;
    $this->timestamp = time();
    $this->score = 0;
  }

  function getId() { return $this->id; }

  function getKept() { return $this->kept; }
  function setKept($kept) { $this->kept = $kept; }

  function getAmount() { return $this->amount; }
  
  function getRate() { return $this->rate; }

  function getOwnerEmail() { return $this->owner_email; }

  function getTimestamp() { return $this->timestamp; }

  function getScore() { return $this->score; }
  function setScore($score) { $this->score = $score; }

}
