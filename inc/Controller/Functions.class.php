<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/19/2018
 * Time: 8:26 PM
 */

class Functions {
    function __construct() {

    }

    /* function to move one index to another
     * @param $array - the array that has indexes you want to move
     * @param $a - the index you want to move from
     * @param $b - the index you want to move to
     * @return $array - the array with newly moved indexes
     * */
    function moveElement(&$array, $a, $b) {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
        return $array;
    }//end moveElement

    /* function to set the second param for the executeQuery function as an empty array
     * @return $params - returns an empty array
     */
    function paramsIsZero() {
        $params = array();
        return $params;
    }//end paramsIsZero

    /* function that sorts an associative array from greatest to least value
		 * @param $array - the chosen array to be sorted
		 * @param $key - the name of the key that will be used to help compare two values in the associative array
		 * @param $value - the name of the value that be used to help compare two values in the associative array
		 * @return $array - the chosen array but sorted from greatest to least
		 * */
    function sortArray($array, $key, $value) {
        for($i = 0; $i < sizeof($array); $i++) {
            for($h = 0; $h < sizeof($array) - $i - 1; $h++) {
                if ($array[$h][$value] < $array[$h + 1][$value]) {
                    $tempID = $array[$h][$key];
                    $tempCount = $array[$h][$value];
                    $array[$h][$key] = $array[$h + 1][$key];
                    $array[$h][$value] = $array[$h + 1][$value];
                    $array[$h + 1][$key] = $tempID;
                    $array[$h + 1][$value] = $tempCount;
                }
            }
        }
        return $array;
    }//end sortArray

    /* function to test if the MySQL values that was fetch is null
     * @param $sqlData - data that was fetched from MySQL
     * @param String - a string that spells out "None" if the value is null
     * @return $sqlData - the data that was fetched if it was found to be not null
     */
    function testSQLNullValue($sqlData) {
        if($sqlData === "NULL") {
            return "None";
        }else {
            return $sqlData;
        }
    }//end testSQLNullValue

}//end Functions