<?php

trait UserAuth{
    public $authID;
    public $authOwner;
    public $authPass;
    public $authRole;
    public $authActive;
    public $authQuestion;
    public $authAnswer;

    public $authOwnerFound = false;
    public $authPassValid = false; 
    public $authTmpPass="";
    public $authUpdatePass = false;
    public $authJWTtoken="";
    public $authDeactivate = 0;
    public $authActivated = 1;
}