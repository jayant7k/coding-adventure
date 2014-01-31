<?php
class myParent
{
    var $myVar = "world";
    function hello()
    {
        echo $this->myVar."\n";      
    }
}

class myFirstChild extends myParent
{
    var $myVar[] = array('a'=>"earth");
}

class mySecondChild extends myParent
{
    var $myVar[] = array('b'=>"planet");
}

$first = new myFirstChild();
$first->hello();

$second = new mySecondChild();
$second->hello();

<script src="https://gist.github.com/jayant7k/5682313.js"></script>
?>