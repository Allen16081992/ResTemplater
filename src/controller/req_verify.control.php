<?php
  class Separator extends Account {

      protected function verifyForm($formFields) {
          switch ($formFields) {
              case 'login':
                $this->loginUser($formFields);
                break;
              case 'sign_up':
                $this->signupUser($formFields);
                break;
              default:
                header('location: ../woops.html');
                break;
          }
      }

  }