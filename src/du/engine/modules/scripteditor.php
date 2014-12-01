<?php

class ScriptEditor
{
    private $str = ''; // file content
    private $filename = ''; // file name
    private $errstr = 'Нет ошибок'; // error string
    private $start = 0; // start and end position of finding text
    private $end = 0;
    private $tmppos = 0; // tmp position

    public function __construct($filename)
    {
        $this->filename = realpath($filename);
    }

    // output string from start to end position
    public function __toString()
    {
        return substr($this->str, $this->start, $this->end - $this->start);
    }

    // resets start and end position
    public function reset()
    {
        $this->start = 0;
        $this->end = strlen($this->str);
        return true;
    }

    // increments start position
    public function incStart(){
        if($this->start >= $this->end)
            return false;
        $this->start++;
        return true;
    }
    // load file
    public function load()
    { // return false if error, true if success
        if (false === $this->str = file_get_contents($this->filename)) {
            $this->errstr = "Не могу загрузить файл '$this->filename'";
            return false;
        }
        $this->reset();
        return true;
    }

    // save file
    public function save()
    { // return false if error, true if success
        if (false === file_put_contents($this->filename, $this->str)) {
            $this->errstr = "Не могу сохранить файл '$this->filename'";
            return false;
        }
        return true;
    }

    // set start and end pointers to function boundaries
    public function selectFunction($name)
    {
        if (false === $pos = strpos($this->str, 'function ' . $name, $this->start) or $pos >= $this->end) {
            $this->errstr = "Не могу найти функцию '$name' в файле '$this->filename'";
            return false;
        }
        if (false === $this->findBoundaries($start, $end, $pos, '{', '}')) {
            $this->errstr = "Не могу найти функцию '$name' в файле '$this->filename'";
            return false;
        }
        $this->start = $start;
        $this->end = $end;
        return true;
    }

    // set start and end pointers to function boundaries
    public function selectBoundaries($start_symb, $end_symb, $tmp_include = false)
    {
        if ($tmp_include)
        {
            if (false === $start = strrpos(substr($this->str, 0, $this->tmppos), $start_symb) or $start < $this->start) {
                $this->errstr = "Не могу найти строку '$str' в файле '$this->filename'";
                return false;
            }
        } else
            $start = $this->start;
        if (false === $this->findBoundaries($start, $end, $start, $start_symb, $end_symb)) {
            $this->errstr = "Не могу разобрать файл '$this->filename'";
            return false;
        }
        $this->start = $start;
        $this->end = $end;
        return true;
    }

    // set start position
    public function findStart($str)
    {
        if (false === $pos = strpos($this->str, $str, $this->start) or $pos >= $this->end) {
            $this->errstr = "Не могу найти строку '$str' в файле '$this->filename'";
            return false;
        }
        $this->start = $pos;
        return true;
    }

    // set start position
    public function findBeforeStart($str)
    {
        if (false === $pos = strrpos(substr($this->str, 0, $this->start), $str)) {
            $this->errstr = "Не могу найти строку '$str' в файле '$this->filename'";
            return false;
        }
        $this->start = $pos;
        return true;
    }

    // set tmp position
    public function findTmp($str)
    {
        if (false === $pos = strpos($this->str, $str, $this->start) or $pos >= $this->end) {
            $this->errstr = "Не могу найти строку '$str' в файле '$this->filename'";
            return false;
        }
        $this->tmppos = $pos;
        return true;
    }

    // set end position
    public function findEnd($str)
    {
        if (false === $pos = strpos($this->str, $str, $this->start) or $pos > $this->end) {
            $this->errstr = "Не могу найти строку '$str' в файле '$this->filename'";
            return false;
        }
        $this->end = $pos + 1;
        return true;
    }

    // set end position
    public function findAfterEnd($str)
    {
        if (false === $pos = strpos($this->str, $str, $this->end) or $pos > $this->end) {
            $this->errstr = "Не могу найти строку '$str' в файле '$this->filename'";
            return false;
        }
        $this->end = $pos + 1;
        return true;
    }

    // founds boundaries like { { } }
    private function findBoundaries(&$start, &$end, $startpos, $start_symb, $end_symb)
    {
        if (false === $start = strpos($this->str, $start_symb, $startpos) or $start >= $this->end)
            return false;
        $end = $start + 1;
        $cnt_start = 1;
        $cnt_end = 0;
        while ($cnt_start != $cnt_end) {
            if ($end >= $this->end)
                return false;
            if ($this->str[$end] == $end_symb)
                $cnt_end++;
            elseif ($this->str[$end] == $start_symb)
                $cnt_start++;
            $end++;
        }
        return true;
    }

    // set start position on new line
    public function gotoNewLine()
    {
        if (false === $pos = strpos($this->str, "\n", $this->start) or $pos > $this->end) {
            $this->errstr = "Не могу перейти на новую строку в файле '$this->filename'";
            return false;
        }
        $this->start = $pos + 1;
        return true;
    }

    // removes line from start position if she is empty
    public function removeEmptyLine()
    {
        if (false === $pos = strpos($this->str, "\n", $this->start)) {
            $this->errstr = "Не могу найти новую строку в файле '$this->filename'";
            return false;
        }
        $pos++;
        $line = substr($this->str, $this->start, $pos - $this->start);
        if (trim($line) != '') {
            $this->errstr = "В файле '$this->filename' текущая строка не пустая";
            return false;
        }
        $this->str = substr($this->str, 0, $this->start) . substr($this->str, $pos);
        if($pos < $this->end)
            $this->end -= strlen($line);
        if ($this->tmppos > $pos)
            $this->tmppos -= $pos - $this->start;
        elseif ($this->tmppos > $this->start)
            $this->tmppos = $this->start;
        return true;
    }

    // insert text from start position
    public function insert($str)
    {
        $this->str = substr($this->str, 0, $this->start) . $str . substr($this->str, $this->start);
        $l = strlen($str);
        $this->end += $l;
        if ($this->tmppos > $this->start)
            $this->tmppos += $l;
        return true;
    }

    // removes selected text
    public function remove()
    {
        $this->str = substr($this->str, 0, $this->start) . substr($this->str, $this->end);
        if ($this->tmppos > $this->end)
            $this->tmppos -= $this->end - $this->start;
        elseif ($this->tmppos > $this->start)
            $this->tmppos = $this->start;
        $this->end = $this->start;
        return true;
    }

    // return last error description
    public function error()
    {
        return $this->errstr;
    }
}
