<?php

require_once 'Record.php';

class RecordTest
{
	public Record $record;

	public function setUp($save = false)
	{
		$this->record = new Record('book', ['isbn' => '123456789', 'title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'createdDate' => new DateTime('1925-05-25'),]);
		if ($save) {
			$this->record->save();
		}
	}

	public function testSetTitle()
	{
		$record = $this->record ?? $this->setUp();
		$record->setTitle('The Greater Gatsby');
		if ('The Greater Gatsby' === $record->getTitle()) {
			$record->setTitle('The Great Gatsby');
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		};
		return false;
	}

	public function testGetCreatedDate()
	{
		$record = $this->record ?? $this->setUp();
		$dt = new DateTime('1925-05-25');
		if ($record->getCreatedDate() === $dt) {
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		};
		echo 'FAIL: ' . __FUNCTION__ . PHP_EOL;
		return false;
	}

	public function testGetRecord()
	{
		if (empty($record)) $this->setUp(true);
		$record = Record::getRecord('123456789');
		if (empty($record)) {
			echo 'Record not found' . PHP_EOL;
			return false;
		}
		if ($record->getISBN() != '123456789' || $record->getTitle() != 'The Great Gatsby' || $record->getAuthor() != 'F. Scott Fitzgerald') {
			echo 'FAIL: Catalog did not return record with isbn 123456789 - The Great Gatsby - F. Scott Fitzgerald' . PHP_EOL;
			return false;
		}
		echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
		return true;
	}

	public function testGetTitle()
	{
		$record = $this->record ?? $this->setUp();
		if ('The Great Gatsby' === $record->getTitle()) {
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		};
		echo 'FAIL: ' . __FUNCTION__ . PHP_EOL;
		return false;
	}

	public function testValidateData()
	{
		$data = ['isbn' => '', 'title' => '', 'author' => '',];

		foreach ($data as $key => $datum) {
			if (Record::validateData($data)) {
				echo $key . ' failed to validate' . PHP_EOL;
				return false;
			}
		}
		echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
		return true;
	}

	public function testGetAuthor()
	{
		$record = $this->record ?? $this->setUp();
		if ('F. Scott Fitzgerald' === $record->getAuthor()) {
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		};
		echo 'FAIL: ' . __FUNCTION__ . PHP_EOL;
		return false;
	}

	public function testGetISBN()
	{
		$record = $this->record ?? $this->setUp();
		if ('123456789' === $record->getISBN()) {
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		};
		echo 'FAIL: ' . __FUNCTION__ . PHP_EOL;
		return false;
	}

	public function testGetType()
	{
		$record = $this->record ?? $this->setUp();
		if (ucwords('book') === $record->getType()) {
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		};
		echo 'FAIL: ' . __FUNCTION__ . PHP_EOL;
		return false;
	}

	public function testSetType()
	{
		$record = $this->record ?? $this->setUp();
		$record->setType('audio');
		if (ucwords('audio') === $record->getType()) {
			$record->setType('book');
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		}
		return false;
	}

	public function testSetCreatedDate()
	{
		$record = $this->record ?? $this->setUp();
		$dt = new DateTime('2022-05-25');
		$record->setCreatedDate($dt);
		if ($record->getCreatedDate() === $dt) {
			$record->setCreatedDate(new DateTime('1925-05-25'));
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		}
		return false;
	}

	public function test__construct()
	{
		$record = new Record('book', ['isbn' => 'testing', 'title' => 'The Greater Gatsby', 'author' => 'F. Scott Fitzgerald', 'createdDate' => new DateTime('1925-05-25'),]);
		if ($record->getISBN() != 'testing' || $record->getTitle() != 'The Greater Gatsby' || $record->getAuthor() != 'F. Scott Fitzgerald') {
			echo 'FAIL: Catalog did not return record with isbn testing - The Great Gatsby - F. Scott Fitzgerald' . PHP_EOL;
			return false;
		}
		echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
		return true;
	}

	public function testSetISBN()
	{
		$record = $this->record ?? $this->setUp();
		$record->setISBN('1234567890');//123456789
		if ($record->getISBN() === '1234567890') {
			$record->setISBN('123456789');
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		}
		echo 'FAIL: Catalog did not return expected isbn: 1234567890' . PHP_EOL;
		return false;
	}

	public function testSave()
	{
		$record = $this->record ?? $this->setUp();
		$record->save();
		$record = Record::getRecord('123456789');
		if (empty($record)) {
			echo 'ERROR Record not found' . PHP_EOL;
			return false;
		}

		if (!file_exists('./catalog/' . $record->getType() . '/' . $record->getISBN())) {
			echo 'FAIL: Catalog file associated with record with isbn 123456789 - The Great Gatsby - F. Scott Fitzgerald was not created' . PHP_EOL;
			return false;
		}
		echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
		return true;
	}

	public function testDelete()
	{
		$record = $this->record ?? $this->setUp();
		if (file_exists('./catalog/' . $record->getType() . '/' . $record->getISBN())) {
			$record->delete();
			if (file_exists('./catalog/' . $record->getType() . '/' . $record->getISBN())) {
				echo 'FAIL: Catalog did not delete record with isbn 123456789' . PHP_EOL;
				return false;
			}
			$record->save();
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		}
		echo 'ERROR: Test file does not exist' . PHP_EOL;
		return false;
	}

	public function testSetAuthor()
	{
		$record = $this->record ?? $this->setUp();
		$record->setAuthor('Jiminy Cricket');
		if ($record->getAuthor() === 'Jiminy Cricket') {
			$record->setAuthor('F. Scott Fitzgerald');
			echo 'Pass Test: ' . __FUNCTION__ . PHP_EOL;
			return true;
		}
		echo 'FAIL: Catalog did not return expected author' . PHP_EOL;
		return false;
	}
}

echo 'Running tests...' . PHP_EOL;
$test = new RecordTest();
$test->setUp();
$test->testSave();
$test->testDelete();
$test->testGetRecord();
$test->testGetTitle();
$test->testGetAuthor();
$test->testGetISBN();
$test->testGetType();
$test->testSetType();
$test->testSetCreatedDate();
$test->testSetTitle();
$test->testSetAuthor();
$test->testSetISBN();
$test->testValidateData();
echo 'All tests complete!' . PHP_EOL;