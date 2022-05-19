<?php

class RecordTest
{
	public Record $record;

	public function setUp()
	{
		$this->record = new Record('book', ['isbn' => '123456789', 'title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'createdDate' => new DateTime('1925-05-25'),]);
	}

	public function testSetTitle()
	{
		$record = $this->record ?? $this->setUp();
		$record->setTitle('The Greater Gatsby');
		if ('The Greater Gatsby' === $record->getTitle()) {
			$record->setTitle('The Great Gatsby');
			return true;
		};
		return false;
	}

	public function testGetCreatedDate()
	{
		$record = $this->record ?? $this->setUp();
		$dt = new DateTime('1925-05-25');
		return ($record->getCreatedDate() === $dt);
	}

	public function testGetRecord()
	{
		if (empty($record)) $this->setUp();
		$record = Record::getRecord('1234567890');
		if ($record->getISBN() != '1234567890' || $record->getTitle() != 'The Great Gatsby' || $record->getAuthor() != 'F. Scott Fitzgerald') {
			echo 'FAIL: Catalog did not return record with isbn 1234567890 - The Great Gatsby - F. Scott Fitzgerald';
			return false;
		}
		return true;
	}

	public function testGetTitle()
	{
		$record = $this->record ?? $this->setUp();
		return ('The Great Gatsby' === $record->getTitle());
	}

	public function testValidateData()
	{
		$data = ['isbn' => '', 'title' => '', 'author' => '',];

		foreach ($data as $key => $datum) {
			if (Record::validateData($data)) {
				echo $key . ' failed to validate';
				return false;
			}
		}
		return true;
	}

	public function testGetAuthor()
	{
		$record = $this->record ?? $this->setUp();
		return ('F. Scott Fitzgerald' === $record->getAuthor());
	}

	public function testGetISBN()
	{
		$record = $this->record ?? $this->setUp();
		return ('123456789' === $record->getISBN());
	}

	public function testGetType()
	{
		$record = $this->record ?? $this->setUp();
		return (ucwords('book') === $record->getType());
	}

	public function testSetType()
	{
		$record = $this->record ?? $this->setUp();
		$record->setType('audio');
		if (ucwords('audio') === $record->getType()) {
			$record->setType('book');
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
			return true;
		}
		return false;
	}

	public function test__construct()
	{
		$record = new Record('book', ['isbn' => 'testing', 'title' => 'The Greater Gatsby', 'author' => 'F. Scott Fitzgerald', 'createdDate' => new DateTime('1925-05-25'),]);
		if ($record->getISBN() != 'testing' || $record->getTitle() != 'The Greater Gatsby' || $record->getAuthor() != 'F. Scott Fitzgerald') {
			echo 'FAIL: Catalog did not return record with isbn 1234567890 - The Great Gatsby - F. Scott Fitzgerald';
			return false;
		}
		return true;
	}

	public function testSetISBN()
	{
		$record = $this->record ?? $this->setUp();
		$record->setISBN('1234567890');//123456789
		if ($record->getISBN() === '1234567890') {
			$record->setISBN('123456789');
			return true;
		}
		echo 'FAIL: Catalog did not return expected isbn: 1234567890';
		return false;
	}

	public function testSave()
	{
		$record = $this->record ?? $this->setUp();
		$record->save();
		$record = Record::getRecord('1234567890');
		if (!file_exists('/catalog/'.$record->getType().'/'.$record->getISBN())) {
			echo 'FAIL: Catalog file associated with record with isbn 1234567890 - The Great Gatsby - F. Scott Fitzgerald was not created';
			return false;
		}
		return true;
	}

	public function testDelete()
	{
		$record = $this->record ?? $this->setUp();
		if (file_exists('/catalog/'.$record->getType().'/'.$record->getISBN())) {
			$record->delete();
			if (file_exists('/catalog/'.$record->getType().'/'.$record->getISBN())) {
				echo 'FAIL: Catalog did not delete record with isbn 1234567890';
				return false;
			}
			return true;
		}
		echo 'ERROR: Test file does not exist';
		return false;
	}

	public function testSetAuthor()
	{
		$record = $this->record ?? $this->setUp();
		$record->setAuthor('Jiminy Cricket');
		if ($record->getAuthor() === 'Jiminy Cricket') {
			$record->setAuthor('F. Scott Fitzgerald');
			return true;
		}
		echo 'FAIL: Catalog did not return expected author';
		return false;
	}
}
