<?php

namespace DUVX\Utils;

class StringBuilder
{
  /**
   * @var string
   */
  protected $str;

  function __construct($str = '')
  {
    $this->str = $str;
  }


  function append($str = '')
  {
    $this->str .= $str;
    return $this;
  }

  function prepend($str = '')
  {
    $this->str = $str . $this->str;
    return $this;
  }

  function toString()
  {
    return $this->str;
  }

  // Insert a string at a given index
  public function insert($index, $string)
  {
    $this->str = substr($this->str, 0, $index) . $string . substr($this->str, $index);
    return $this;
  }

  // Replace a substring
  public function replace($from, $to)
  {
    $this->str = str_replace($from, $to, $this->str);
    return $this;
  }

  // Remove a substring
  public function remove($start, $length)
  {
    $this->str = substr($this->str, 0, $start) . substr($this->str, $start + $length);
    return $this;
  }

  // Get char at index
  public function charAt($index)
  {
    return $this->str[$index];
  }

  // Get substring
  public function substring($start, $end)
  {
    return substr($this->str, $start, $end - $start);
  }

  // Get length
  public function length()
  {
    return strlen($this->str);
  }

  public function removeLast()
  {
    $this->str = substr($this->str, 0, -1);
    return $this;
  }

  // Reverse the string
  public function reverse()
  {
    $this->str = strrev($this->str);
    return $this;
  }
}
