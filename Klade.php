<?php

/**
 * Klade 
 * 
 * @author    Easton Elliott <easton@geekness.eu> 
 * @license   MIT
 * @version   0.2
 */
class Klade {

    /**
     * The log filename
     * @var string 
     */
    public $filename;

    /**
     * The file handle
     * @var resource 
     */
    private $logFileHandle;

    /**
     * Close the file handle 
     */
    public function __destruct() {
        fclose($this->logFileHandle);
    }

    /**
     * Set the log file
     * @param string $filename Log filename
     * @access public
     * 
     */
    public function setLog($filename) {
        //Create a handle for the file
        $this->logFileHandle = fopen($filename, 'a+b');
        //If the file doesn't exist, attempt to create it
        if (file_exists($filename) == false) {
            if ($this->logFileHandle) {
                $this->filename = $filename;
            } else {
                throw new exception("Cannot create file $filename");
            }
            //If the file exists, check if it's writable   
        } else if (is_writable($filename)) {
            $this->filename = $filename;
        } else {
            throw new exception("Cannot write to $filename");
        }
    }

    /**
     * Add a log entry
     * @param string $log_message Log message
     * @access public
     * @return boolean
     */
    public function addEntry($log_message) {
        //Check if the message is empty, if so return false
        if (!isset($log_message) || trim($log_message) !== '') {
            //Prepend the date to the log message and add a line break
            $log_message = "[" . date('M d H:i:s') . "] " . $log_message . "\n";
            if (fwrite($this->logFileHandle, $log_message) === false) {
                throw new exception("Cannot write to file $this->filename");
            } else {
                return true;
            }
        } else {
            throw new exception("Log message cannot be empty");
        }
    }

    /**
     * Search for a log entry 
     * @param string $log_message
     * @access public
     * @return mixed Search result
     */
    public function findEntry($log_message) {

        //Thanks to http://stackoverflow.com/revisions/b544e742-b140-44ff-8ae3-b9df9bba4cd9/view-source

        $log_contents = file_get_contents($this->filename);

        //Escape special characters
        $pattern = preg_quote($log_message, '/');
        //Finalize the regular expression and match the entire line
        $pattern = "/^.*$pattern.*\$/m";

        if (preg_match_all($pattern, $log_contents, $result)) {
            return($result[0]);
        } else {
            return false;
        }
    }

    /**
     * Truncate the log file
     * @return boolean
     * @throws exception 
     */
    public function truncateLog() {

        if (ftruncate($this->logFileHandle, 0) == false) {
            throw new exception("Cannot truncate file $this->filename");
        } else {
            return true;
        }
    }

}