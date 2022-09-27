<?php
class User
{
    protected string $email;
    protected int $type;
    protected string $firstName;
    protected string $lastName;
    protected string $password;
    
    #region getters
    public function getEmail() { return $this->email; }
    public function getType() { return $this->type; }
    public function getFirstName() { return $this->firstName; }
    public function getLastName() { return $this->lastName; }
    public function getPassword() { return $this->password; }
    #endregion
    #region setters
    public function setEmail($email) { $this->email = $email; }
    public function setType($type) { $this->type = $type; }
    public function setFirstName($firstName) { $this->firstName = $firstName; }
    public function setLastName($lastName) { $this->lastName = $lastName; }
    public function setPassword($password) { $this->password = $password; }
    #endregion
}
