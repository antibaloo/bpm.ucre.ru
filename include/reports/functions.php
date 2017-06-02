<?
function GetSubStructure($departmentID){
	$subDepartments = CIntranetUtils::getSubDepartments($departmentID);
	if ($subDepartments == NULL) return $departmentID;
	else {
		$result = $departmentID;
		foreach ($subDepartments as $subDepartment){
			$result.= "|".GetSubStructure($subDepartment);
		}
		return $result;
	}
}
?>