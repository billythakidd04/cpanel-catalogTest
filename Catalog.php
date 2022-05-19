<?php
require_once 'Record.php';
// Manage the interface for accessing the catalog database
class Catalog
{
	// add a method to get a list of all the records in the catalog
	public function getAllRecords(): array
	{
		$records = [];
		// get all types (directories) in the main directory
		$types = $this->getAllTypes();
		// loop over each type directory
		foreach ($types as $type) {
			$records[$type] = $this->getAllRecordsOfType($type);
		}
		return $records;
	}

	// TODO add a method to get a specific record from the catalog
	public function getRecord(string $id)
	{
		return Record::getRecord($id);
	}

	// TODO add a method to add a record to the catalog

	/**
	 * @throws Exception
	 */
	public function addRecord(string $type, array $data)
	{
		if (Record::validateData($data)) {
			if(!in_array(ucwords($type), $this->getAllTypes())) {
				mkdir('/catalog/' . preg_replace('/\s/', '_', ucwords($type)));
			}

			$record = new Record($type, $data);
			$record->save();
			return true;
		}
		return false;
	}

	// TODO add a method to mark a record as deleted from the catalog
	public function deleteRecord(string $id): bool
	{
		$record = $this->getRecord($id);
		if ($record) {
			return $record->delete();
		}
		return false;
	}

	/**
	 * Returns a list of all record types in the catalog
	 *
	 * @return array|false
	 */
	public function getAllTypes()
	{
		return array_diff(scandir('/catalog'), ['.', '..']);
	}

	/**
	 * Returns a list of all the records of a given type or false if the type is not found
	 *
	 * @param string $type
	 * @return Record[]|false
	 */
	public function getAllRecordsOfType(string $type)
	{
		foreach ($this->getAllTypes() as $recordType) {
			if ($recordType === ucwords($type)) {
				$files = array_diff(scandir('/catalog/' . $recordType), ['.', '..']);
				foreach ($files as $file) {
					$fp = fopen("/catalog/$recordType/$file", 'r');
					$recordArray = [];
					foreach (explode("\n", fread($fp, filesize("/catalog/$recordType/$file"))) as $line) {
						$data = explode('=', $line);
						$recordArray[$data[0]] = $data[1];
					}
					if (Record::validateData($recordArray)) {
						$record = new Record($recordType, $recordArray);
						$records[] = $record;
					}
				}
				return empty($records) ? false : $records;
			}
		}
		return false;
	}
}