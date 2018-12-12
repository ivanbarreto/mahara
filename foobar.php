<?php

for ($i = 1; $i<= 100; $i++){
    //3*5 = 15
    //if it's divisible by 15,
    //it's also divisible by 5 AND 3
    if($i % 15 == 0)  {
        echo "foobar";
    } 
    elseif ($i % 5 == 0) {
        echo "bar";
    } 
    elseif ($i % 3 == 0) {
        echo "foo";
    }
    else{
        echo $i;
    }
    //just to make it prettier
    if ($i != 100){
        echo ", ";
    }
}
