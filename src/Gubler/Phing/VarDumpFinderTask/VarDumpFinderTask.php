<?php
/**
 * Phing task to check for var_dump() in a fileset
 *
 * @author Daryl Gubler <daryl@dev88.co>
 * @copyright Copyright (c) 2016 Daryl Gubler
 * @license MIT License
 */

namespace Gubler\Phing\VarDumpFinderTask;

/**
 * Phing task to check fileset for instances of var_dump()
 *
 * @package Odev\Contrib
 */
class VarDumpFinderTask extends \Task
{
    const VAR_DUMP = 'var_dump';

    /** @var array */
    protected $_fileSets = array();

    /** @var bool */
    protected $_findInComments = false;

    /** @var bool */
    protected $_haltOnMatch = false;

    /**
     * Phing entry point
     *
     * @throws \BuildException
     */
    public function main()
    {
        if (!count($this->_fileSets)) {
            throw new \BuildException("Missing a nested fileset");
        }

        $project = $this->getProject();
        $violations = array();

        foreach ($this->_fileSets as $fs) {
            $files = $fs->getDirectoryScanner($project)->getIncludedFiles();
            $dir   = $fs->getDir($project)->getPath();

            foreach ($files as &$file) {
                $fileName = $dir . DIRECTORY_SEPARATOR . $file;
                $results = $this->findVarDump($fileName);
                foreach ($results as $row) {
                    $violations[] = $fileName.':'.$row;
                }
            }
        }

        foreach ($violations as $violation) {
            $this->log($violation);
        }

        if (!empty($violations) && $this->_haltOnMatch) {
            throw new \BuildException('Found traces of var_dump in filesets');
        }
    }

    /**
     * Check a file for var_dump() usage
     *
     * @param string $file
     *
     * @return array
     *
     * @throws BuildException
     */
    public function findVarDump($file)
    {
        if (!file_exists($file)) {
            throw new \BuildException('File Not Found: '.$file);
        }

        $tokens = token_get_all(file_get_contents($file));
        $violations = array();

        foreach ($tokens as $token) {
            if (
                is_array($token) &&
                $this->tokenIsInvalidVarDump($token, $this->_findInComments)
            ) {
                $violations[] = $token[2];
            }
        }


        return $violations;
    }

    /**
     * Check a token for invalid usage of var_dump()
     *
     * @param array $token
     * @param bool  $findComments
     *
     * @return bool
     */
    public function tokenIsInvalidVarDump($token, $findComments = false)
    {
        // Find non-comment var_dump();
        if (
            token_name($token[0]) == 'T_STRING' &&
            $token[1] == self::VAR_DUMP
        ) {
            return true;
        }

        // find commented var_dump
        $pattern = '/.*'.self::VAR_DUMP.'.*/us';
        if (
            $findComments &&
            (
                token_name($token[0]) == 'T_COMMENT' ||
                token_name($token[0]) == 'T_DOC_COMMENT'
            ) &&
            preg_match($pattern, $token[1])
        ) {
            return true;
        }

        return false;
    }

    /**
     * Create file set for checking
     * @return mixed
     */
    public function createFileSet()
    {
        $num = array_push($this->_fileSets, new \FileSet());

        return $this->_fileSets[$num-1];
    }

    /**
     * Set if task should halt on match
     *
     * @param null|bool $haltOnMatch
     */
    public function setHaltOnMatch($haltOnMatch = false)
    {
        if (is_bool($haltOnMatch)) {
            $this->_haltOnMatch = $haltOnMatch;
        }
    }

    /**
     * Set if task should should find var_dump() in comments
     *
     * @param null|bool $findInComments
     */
    public function setFindInComments($findInComments = false)
    {
        if (is_bool($findInComments)) {
            $this->_findInComments = $findInComments;
        }
    }
}
