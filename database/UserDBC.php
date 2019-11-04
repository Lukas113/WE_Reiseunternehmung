<?php

namespace database;

use entities\User;
use entities\Participant;

/**
 * Provides secure access to the database of {@link User} releated queries
 * <ul>
 * <li>{@link createUser($user)}</li>
 * <li>{@link findUserByEmail($search, $inputType)}</li>
 * <li>{@link setTimeStamp()}</li>
 * <li>{@link findUserById($userId)}</li>
 * <li>{@link getAllUsers()}</li>
 * <li>{@link updatePassword($user)}</li>
 * <li>{@link deleteUser($user)}</li>
 * <li>{@link checkLastAdmin($user)}</li>
 * <li>{@link createParticipant($participant)}</li>
 * <li>{@link findParticipants($user)}</li>
 * <li>{@link findParticipantById($participantId)}</li>
 * <li>{@link findParticipantsToTrip($tripId)}</li>
 * <li>{@link deleteParticipant($participant)}</li>
 * <li>{@link updateRole($user)}</li>
 * <li>{@link checkByEmail($email)}</li>
 * </ul>
 * @author Lukas
 */
class UserDBC extends DBConnector {
    
    /**
     * Stores the {@link User} into the database
     * @param User $user
     * @return boolean
     */
    public function createUser($user){
        $stmt = $this->mysqliInstance->prepare("INSERT INTO user VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ssssisssssi', $firstName, $lastName, $gender, $street, $zipCode,
                $location, $email, $role, $birthDate, $password, $deleted);
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $gender = $user->getGender();
        $street = $user->getStreet();
        $zipCode = $user->getZipCode();
        $location = $user->getLocation();
        $email = $user->getEmail();
        $role = $user->getRole();
        $birthDate = $user->getBirthDate();
        $password = $user->getPassword();
        $deleted = intval(false);
        return $this->executeInsert($stmt);
    }
    
    
    /**
     * Finds  the {@link User} to the given email if available for login process
     * @param User $user
     * @param String $inputType describes the data object of $search
     * @return boolean|User
     */
    public function findUserByEmail($search, $inputType = "object"){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM user where email = ? and deleted = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('si', $email, $deleted);
        if ($inputType == "object") {
        $email = $search->getEmail();
        } elseif ($inputType == "email") {
            $email = $search;
        } else {
            exit;
            
        }
        $deleted = intval(false);
        $stmt->execute();
        $userObj = $stmt->get_result()->fetch_object("entities\User");
        
        //checks whether the given email from a User exists
        $stmt->close();
        if($userObj){
            if ($inputType == "object") {
                return $userObj;
            } else {
                return true;
            }
        }else{
            return false;
        }
    }
    
    /**
     * Sets the current timestamp for booking to avoid to fast booking of a {@link Trip}
     * @return boolean
     */
    public function setTimeStamp(){
        $stmt = $this->mysqliInstance->prepare("UPDATE user SET lastBooking = ? WHERE id = ? AND deleted = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('dii', $lastBooking, $id, $deleted);
        $lastBooking = \time();
        $id = $_SESSION['userId'];
        $deleted = intval(false);
        return $this->executeInsert($stmt);
    }
    
    /**
     * Finds the {@link User} by the given id (teleted Users also)
     * @param int $userId
     * @return boolean|User
     */
    public function findUserById($userId){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM user where id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $userId;
        $stmt->execute();
        $userObj = $stmt->get_result()->fetch_object("entities\User");
        
        //checks whether the User exists
        $stmt->close();
        if($userObj){
            return $userObj;
        }else{
            return false;
        }
    }
    
    /**
     * Gets all {@link User} registered ordered by firstName asc
     * @return boolean|array
     */
    public function getAllUsers(){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM user WHERE deleted = ? ORDER BY firstName ASC");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $deleted);
        $deleted = intval(false);
        $stmt->execute();
        $users = array();
        $result = $stmt->get_result();
        while($user = $result->fetch_object("entities\User")){
            array_push($users, $user);
        }

        $stmt->close();
        return $users;
    }
    
    /**
     * Updates the {@link User} password (the new password must be stored in the {@link User})
     * @param User $user
     * @return type
     */
    public function updatePassword($user){
        $stmt = $this->mysqliInstance->prepare("UPDATE user SET password = ? WHERE id = ? AND deleted = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('sii', $password, $id, $deleted);
        $password = $user->getPassword();
        $id = $user->getId();
        $deleted = intval(false);
        return $this->executeInsert($stmt);
    }
    
    /**
     * Removes the {@link User} from access of several functions<br>
     * This function doesn't provide a real deletion of a {@link User} 
     * to not lose the {@link Trip} related and important data
     * @param User $user
     * @return boolean
     */
    public function deleteUser($user){
        if(!($this->checkLastAdmin($user))){
            return false;
        }
        $stmt = $this->mysqliInstance->prepare("UPDATE user SET deleted = ? WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ii', $deleted, $id);
        $deleted = intval(true);
        $id = $user->getId();
        return $this->executeDelete($stmt);
    }
    
    /**
     * Checks for the permission to delete or change the role for the last admin
     * @param User $user
     * @return boolean
     */
    public function checkLastAdmin($user){
        $user = $this->findUserById($user->getId());
        if(!$user){
            return false;
        }
        //Checks whether it is the last admin to delete, then it rejects the delete process
        if($user->getRole() == "admin"){
            $users = $this->getAllUsers();
            if(!$users){
                return false;
            }
            $count = 0;
            foreach($users as $u){
                if($u->getRole() == "admin"){
                    $count++;
                }
            }
            if($count <= 1){
                return false;
            }
        }
        return true;
    }
    
    /**
     * Stores the {@link Participant} in relation to the {@link User}
     * @param Participant $participant
     * @return boolean|int
     */
    public function createParticipant($participant){
        $stmt = $this->mysqliInstance->prepare("INSERT INTO participant VALUES (NULL, ?, ?, ?, ?, ?)");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('sssii', $firstName, $lastName, $birthDate, $deleted, $fk_user_id);
        $firstName = $participant->getFirstName();
        $lastName = $participant->getLastName();
        $birthDate = $participant->getBirthDate();
        $deleted = intval(false);
        $fk_user_id = $participant->getFkUserId();
        return $this->executeInsert($stmt);
    }
    
    /**
     * Finds any number of {@link Participant} related to the given {@link User}
     * @param User $user
     * @return boolean|array
     */
    public function findParticipants($user){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM participant WHERE fk_user_id = ? AND deleted = ? ORDER BY firstName ASC");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ii', $fk_user_id, $deleted);
        $fk_user_id = $user->getId();
        $deleted = intval(false);
        $stmt->execute();
        $participants = array();
        $result = $stmt->get_result();
        while($participant = $result->fetch_object("entities\Participant")){
            array_push($participants, $participant);
        }
        
        $stmt->close();
        return $participants;
    }
    
    /**
     * Finds the {@link Participant} by the given id (teleted Participants also)
     * @param int $participantId
     * @return boolean|Participant
     */
    public function findParticipantById($participantId){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM participant where id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $participantId;
        $stmt->execute();
        $participantObj = $stmt->get_result()->fetch_object("entities\Participant");
        
        //checks whether the Participant exists
        $stmt->close();
        if($participantObj){
            return $participantObj;
        }else{
            return false;
        }
    }
    
    /**
     * Finds all {@link Participant} according to the {@link Trip} (deletion of {@link Participant} is ignored)
     * @param int $tripId
     * @return boolean|array
     */
    public function findParticipantsToTrip($tripId){
        $stmt = $this->mysqliInstance->prepare("SELECT fk_participant_id FROM tripparticipant WHERE fk_trip_id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $fk_trip_id);
        $fk_trip_id = $tripId;
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        //Gets the real Participant objects into an array
        $participants = array();
        while($participantId = $result->fetch_assoc()){
            $participant = $this->findParticipantById($participantId["fk_participant_id"]);
            array_push($participants, $participant);
        }

        return $participants;
    }
    
    /**
     * Removes {@link Participant} from access of several functions<br>
     * This function doesn't provide real deletion of {@link Participant} 
     * to ensure access to {@link Trip} related important data
     * @param Participant $participant
     * @return boolean
     */
    public function deleteParticipant($participant){
        $stmt = $this->mysqliInstance->prepare("UPDATE participant SET deleted = ? WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ii', $deleted, $id);
        $deleted = intval(true);
        $id = $participant->getId();
        return $this->executeInsert($stmt);
    }

    
    /**
     * Updates the role of the given {@link User}
     * @param User $user
     * @return boolean
     */
    public function updateRole($user){
        $stmt = $this->mysqliInstance->prepare("UPDATE user SET role = ? WHERE id = ? AND deleted = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('sii', $role, $id, $deleted);
        $role = $user->getRole();
        $id = $user->getId();
        $deleted = intval(false);
        return $this->executeInsert($stmt);
    }
    
    /**
     * Checks whether the email already exists in the database
     * @param String $email
     * @return boolean|User
     */
    public function checkByEmail($email){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM user where email = ? and deleted = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('si', $email, $deleted);
        $deleted = intval(false);
        $stmt->execute();
        $userObj = $stmt->get_result()->fetch_object("entities\User");
        
        //checks whether the given email from a User exists
        $stmt->close();
        if($userObj){
            return true;
        }else{
            return false;
        }
    }
}
