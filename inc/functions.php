<?php
	///* FUNCTIONS *///

	/* function to set the second param for the executeQuery function as an empty array
     * @return $params - returns an empty array
	 */
	function paramsIsZero() {
		$params = array();
		return $params;
	}

	/* function to test if the MySQL values that was fetech is null
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
	}

?>
