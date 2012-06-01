<?php

/**
 * Klade 
 * 
 * @link      https://github.com/snacsnoc/klade
 * @author    Easton Elliott <easton@geekness.eu> 
 * @license   MIT
 * @version   0.2.1
 */
class Klade {

    /**
     * The log filename
     * @var string
     */
    public $filename;

    /**
     * Set the log file
     * @param string $filename Log filename
     * @access public
     * 
     */
    public function setLog($filename) {
        //Open a handle for the file
        $file_handle = fopen($filename, 'a+');

        if ($file_handle) {
            $this->filename = $filename;
        } else {
            throw new exception("Cannot open file $filename");
        }

        fclose($file_handle);
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
            //Open a handle for the file
            $file_handle = fopen($this->filename, 'a');

            //Prepend the date to the log message and add a line break
            $log_message = "[" . date('Y-m-d H:i:s') . "] " . trim($log_message) . "\n";

            if (fwrite($file_handle, $log_message) == false) {
                throw new exception("Cannot write to file $this->filename");
            } else {
                fclose($file_handle);
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
     * 
     */
    public function truncateLog() {
        $file_handle = fopen($this->filename, 'a+');
        if (ftruncate($file_handle, 0) == false) {
            throw new exception("Cannot truncate file $this->filename");
        } else {
            fclose($file_handle);
            return true;
        }
    }

}