<?php // Load PHP files      
    require_once './validator.control.php';

    class ResumeControl extends Resume {
        use ErrorHandler;
        use Validators;

        private $formFields;

        public function __construct($formFields) {
           $this->formFields = $formFields;
        }

        public function verifyInfo() {
            // Perform validation
            $this->emptyInput($this->formFields);

            // Continue processing
            // New Resume
            if (isset($this->formFields['creResume'])) {

            }
            // Update Resume
            elseif (isset($this->formFields['saveResume'])) {
                
            }
            // Remove Resume
            elseif (isset($this->formFields['delResume'])) {
    
            }
            // Save Resume Image
            elseif (isset($this->formFields['saveImg'])) {
                
            }
            // Remove Resume Image
            elseif (isset($this->formFields['delImg'])) {
                
            }
            // New Work Experience
            elseif (isset($this->formFields['addExperience'])) {
                
            }
            // Update Work Experience
            elseif (isset($this->formFields['saveExperience'])) {
                
            }
            // Remove Work Experience
            elseif (isset($this->formFields['trashExperience'])) {
        
            }  
            // New Education
            elseif (isset($this->formFields['addEducation'])) {
                
            }
            // Update Education
            elseif (isset($this->formFields['saveEducation'])) {
                
            }
            // Remove Education
            elseif (isset($this->formFields['trashEducation'])) {
        
            }    
        }
    }