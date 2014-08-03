<?php

namespace FBDataExtractor;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserProfile
 *
 * @author gbaskaran
 */
class FBUser {
    
    public function __toString() {
        
        return "\nid = ".$this->id
                ."\nfirstName = ".$this->firstName
                ."\nlastName = ".$this->lastName
                ."\nname = ".$this->name
                ."\nbirthday = ".$this->birthday
                ."\nlocation = ".$this->location
                ."\ntimezone = ".$this->timezone
                ."\ngender = ".$this->gender
                ."\nemail = ".$this->email
                ."\nEducation = ".implode(',', $this->educationHistory)
                ."\nWork = ".  implode(',', $this->workHistory);

    }

    
    
    private $id;
    private  $firstName;
    private  $lastName;
    private  $name;
    private  $birthday;
    private  $location;
    private  $timezone;
    private  $gender;
    private  $email;
    private  $workHistory = array();
    private  $educationHistory = array();
    private $likes;
    private $friendList;
    
    
    public function getFriendList() {
        return $this->friendList;
    }

    public function setFriendList($friendList) {
        $this->friendList = $friendList;
    }

        public function getWorkHistory() {
        return $this->workHistory;
    }

    public function getEducationHistory() {
        return $this->educationHistory;
    }

    public function setWorkHistory($workHistory) {
        $this->workHistory = $workHistory;
    }

    public function setEducationHistory($educationHistory) {
        $this->educationHistory = $educationHistory;
    }

        
    
    public function getLikes() {
        return $this->likes;
    }

    public function setLikes($likes) {
        $this->likes = $likes;
    }

        
    
    function __construct($id) {
        $this->id = $id;
        
    }
    public function getId() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getName() {
        return $this->name;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getTimezone() {
        return $this->timezone;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setTimezone($timezone) {
        $this->timezone = $timezone;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setEmail($email) {
        $this->email = $email;
    }


}
