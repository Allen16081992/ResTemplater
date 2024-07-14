<?php
  class Separator extends Account {

      protected function verifyForm($formFields) {
          switch ($formFields) {
              case $formFields['login']:
                $this->loginUser($formFields);
                break;
              case $formFields['sign_up']:
                $this->signupUser($formFields);
                break;
              default:
                header('location: ../woops.html');
                break;
          }
      }

  }