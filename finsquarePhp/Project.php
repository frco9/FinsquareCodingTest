<?php

require_once "Contribution.php";

Class Project {
  private $id;
  private $required_amount;
  private $total_amount;
  private $avg_rate;
  private $max_rate;
  private $contrib_stack;

  function __construct($required_amount = 500, $total_amount = 0) {
    $this->id = uniqid();
    $this->required_amount = $required_amount;
    $this->total_amount = $total_amount;
    $this->remaining_amount = 0;
    $this->avg_amount = 0;
    $this->avg_rate = 0;
    $this->max_rate = 10;
    $this->contrib_stack = array();
  }

  function addContribution(Contribution $contribution) {
    // Add contribution to array
    array_push($this->contrib_stack, $contribution);
    // Order array
    usort($this->contrib_stack, function($a, $b) {
      if($a->getRate() == $b->getRate()){
       return ($a->getTimestamp() < $b->getTimestamp()) ? -1 : 1;
      }
      return ($a->getRate() < $b->getRate()) ? -1 : 1;
    });
    // Compute score and get stats
    $this->computeScoreAndStats();

    return $this->total_amount;
  }

  function getContributionsToRefund() {
    $filtered_contribs = array_filter($this->contrib_stack, function($contribution) {
                          return $contribution->getKept();
                        });
    return array_map(function($contribution){
      return $contribution->getId();
    }, $filtered_contribs);
  }

  function getTotalAtRate($rate) {
    $filtered_contribs = array_filter($this->contrib_stack, function($contribution) {
                          return (($contribution->getRate() == $rate) && ($contribution->getKept()));
                        });
    return array_map(function($contribution){
      return $contribution->getAmount();
    }, $filtered_contribs);
  }

  function getContributionsAboveScore($score) {
    $filtered_contribs = array_filter($this->contrib_stack, function($contribution) {
                          return ($contribution->getScore() >= $score);
                        });
    return array_map(function($contribution){
      return $contribution->getId();
    }, $filtered_contribs);
  }

  function getAverageRate() {
    return $this->avg_rate;
  }

  function getAverageAmount() {
    return $this->avg_amount;
  }

  function getTotalAmount() {
    return $this->total_amount;
  }

  function getRemainingAmount() {
    return $this->remaining_amount;
  }

  function getContributions() {
    return $this->contrib_stack;
  }

  function getMaxRate() {
    return $this->max_rate;
  }


  private function computeScoreAndStats() {
    $previous_score = 0;
    $rate_sum = 0;
    $amount_sum = 0;
    $kept_contrib_number = 0; 
    $max_rate_flag = false;
    for ($i=0; $i < count($this->contrib_stack); $i++) { 
      $my_contrib = $this->contrib_stack[$i];
      $my_contrib->setScore($previous_score + $my_contrib->getAmount());
      $previous_score = $my_contrib->getScore();
      // Check if contribution must be refunded
      if ($previous_score > $this->required_amount) {
        $my_contrib->setKept(false);
        if (!$max_rate_flag) {
          $this->max_rate = $my_contrib->getRate();
          $max_rate_flag = true;
        }
      }
      // Compute statistics only on kept contributions
      if ($my_contrib->getKept()) {
        $rate_sum += $my_contrib->getRate();
        $amount_sum += $my_contrib->getAmount();
        $kept_contrib_number++;
      }
    }
    $this->total_amount = $amount_sum;
    $this->remaining_amount = max($this->required_amount - $this->total_amount, 0);
    $this->avg_rate = $rate_sum / max($kept_contrib_number, 1);
    $this->avg_amount = $amount_sum / max($kept_contrib_number, 1);
  }
}