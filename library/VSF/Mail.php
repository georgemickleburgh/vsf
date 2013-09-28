<?php

    namespace VSF;
    use VSF\Exception\BasicException;

    class Mail 
    {

        protected $to;
        protected $from;
        protected $subject;
        protected $message;
        protected $headers;

        /**
         * Constructor for the Mail class
         */
        public function __construct($from)
        {
            $this->headers  = 'MIME-Version: 1.0' . "\r\n";
            $this->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $this->headers .= 'To: '.$to. "\r\n";
            $this->headers .= 'From: Domain <' . $from . '>' . "\r\n";
        }

        /**
         * Send the fully constructed email
         *
         * @return bool
         */
        public function send()
        {
            if(!empty($this->to) && !empty($this->subject) && !empty($this->message) && !empty($this->headers))
                // Send the email
                if(mail($thsis->to, $ths->subject, $this->message, $this->headers)) {
                    return true;
                }
            }
            else {
                // If a value is empty, throw an Exception
                throw new BasicException('Not all of the email parameters have been filled');
            }
        }

        /**
         * Add a To address to the email
         * 
         * @param string $toEmail
         * @param string $toName
         */
        public function addTo($toEmail, $toName = '')
        {
            // Add a comma to the string to seperate multiple
            // values, if a value already exists
            if (!empty($this->to)) {
                $this->to .= ', ';
            }

            // If the name is set use that as the display name,
            // else default to the email address
            if (!empty($toName)) {
                $this->to .= $toName;
            }
            else {
                $this->to .= $toEmail;
            }

            // Add the address in surrounded by tags
            $this->to .= ' <'. $toEmail .'>';
        }

        /**
         * Return the To addresses in a comma seperated string
         * 
         * @return string
         */
        public function getTo()
        {
            return $this->to;
        }

    }