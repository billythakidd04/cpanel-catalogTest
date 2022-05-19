<?php

class CatalogTest
{

	public function testAddRecord(): bool
	{
		$catalog = new Catalog();
		$data = ['isbn' => '1234567890', 'title' => 'Test Title', 'author' => 'Test Author', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('book', $data);
		if (!file_exists('/catalog/Book/1234567890')) {
			echo 'FAIL: Record was not saved';
			return false;
		}
		$this->cleanUp();
		return true;
	}

	public function testGetAllRecords()
	{
		$catalog = new Catalog();
		$data = ['isbn' => '1234567890', 'title' => 'Test Title', 'author' => 'Test Author', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('book', $data);
		$data = ['isbn' => '0987654321', 'title' => 'Test Title 2', 'author' => 'Test Author 2', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('book', $data);
		$data = ['isbn' => '0987654322', 'title' => 'Test Title 3', 'author' => 'Test Author 3', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('book', $data);
		$records = $catalog->getAllRecords();
		if (count($records) != 3) {
			echo 'FAIL: Catalog did not return all records';
			return false;
		}
		$this->cleanUp();
		return true;
	}

	public function testGetAllTypes()
	{
		$catalog = new Catalog();
		$data = ['isbn' => '1234567890', 'title' => 'Test Title', 'author' => 'Test Author', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('audio', $data);
		$data = ['isbn' => '0987654321', 'title' => 'Test Title 2', 'author' => 'Test Author 2', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('video', $data);
		$data = ['isbn' => '0987654322', 'title' => 'Test Title 3', 'author' => 'Test Author 3', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('gift_item', $data);
		$types = $catalog->getAllTypes();
		if (count($types) != 3) {
			echo 'FAIL: Catalog did not return all types';
			return false;
		}
		foreach ($types as $type) {
			if (!in_array($type, ['Audio', 'Video', 'Gift Item'])) {
				echo 'FAIL: Catalog did not return all types';
				return false;
			}
		}
		$this->cleanUp();
		return true;
	}

	public function testGetAllRecordsOfType()
	{
		$catalog = new Catalog();
		$data = ['isbn' => '1234567890', 'title' => 'Test Title', 'author' => 'Test Author', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('audio', $data);
		$data = ['isbn' => '0987654321', 'title' => 'Test Title 2', 'author' => 'Test Author 2', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('video', $data);
		$data = ['isbn' => '0987654322', 'title' => 'Test Title 3', 'author' => 'Test Author 3', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('gift_item', $data);
		$records = $catalog->getAllRecordsOfType('audio');
		if (count($records) != 1) {
			echo 'FAIL: Catalog did not return all records of type audio';
			return false;
		}
		$records = $catalog->getAllRecordsOfType('video');
		if (count($records) != 1) {
			echo 'FAIL: Catalog did not return all records of type video';
			return false;
		}
		$records = $catalog->getAllRecordsOfType('gift_item');
		if (count($records) != 1) {
			echo 'FAIL: Catalog did not return all records of type gift_item';
			return false;
		}
		$records = $catalog->getAllRecordsOfType('book');
		if (count($records) != 0) {
			echo 'FAIL: Catalog did not returned erroneous records of type book';
			return false;
		}
		$this->cleanUp();
		return true;
	}

	public function testGetRecord()
	{
		$catalog = new Catalog();
		$data = ['isbn' => '1234567890', 'title' => 'Test Title', 'author' => 'Test Author', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('audio', $data);
		$data = ['isbn' => '0987654321', 'title' => 'Test Title 2', 'author' => 'Test Author 2', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('video', $data);
		$data = ['isbn' => '0987654322', 'title' => 'Test Title 3', 'author' => 'Test Author 3', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('gift_item', $data);
		$record = $catalog->getRecord('1234567890');
		if ($record->getISBN() != '1234567890' || $record->getTitle() != 'Test Title' || $record->getAuthor() != 'Test Author') {
			echo 'FAIL: Catalog did not return record with isbn 1234567890';
			return false;
		}
		$record = $catalog->getRecord('0987654321');
		if ($record->getISBN() != '0987654321' || $record->getTitle() != 'Test Title 2' || $record->getAuthor() != 'Test Author 2') {
			echo 'FAIL: Catalog did not return record with isbn 0987654321';
			return false;
		}
		$record = $catalog->getRecord('0987654322');
		if ($record->getISBN() != '0987654322' || $record->getTitle() != 'Test Title 3' || $record->getAuthor() != 'Test Author 3') {
			echo 'FAIL: Catalog did not return record with isbn 0987654322';
			return false;
		}
		$record = $catalog->getRecord('0987654323');
		if ($record != null) {
			echo 'FAIL: Catalog returned erroneous record';
			return false;
		}
		$this->cleanUp();
		return true;
	}

	public function testDeleteRecord()
	{
		$catalog = new Catalog();
		$data = ['isbn' => '1234567890', 'title' => 'Test Title', 'author' => 'Test Author', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('audio', $data);
		$data = ['isbn' => '0987654321', 'title' => 'Test Title 2', 'author' => 'Test Author 2', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('video', $data);
		$data = ['isbn' => '0987654322', 'title' => 'Test Title 3', 'author' => 'Test Author 3', 'createdDate' => new DateTime('now')];
		$catalog->addRecord('gift_item', $data);
		$catalog->deleteRecord('1234567890');
		$records = $catalog->getAllRecords();
		if (count($records) != 2) {
			echo 'FAIL: Catalog did not delete record with isbn 1234567890';
			return false;
		}
		$this->cleanUp();
		return true;
	}

	public function cleanUp()
	{
		$app = new Catalog();
		$records = $app->getAllRecords();
		/** @var Record $record */
		foreach ($records as $record) {
			unlink('/catalog/' . $record->getType() . '/' . $record->getISBN());
		}
	}
}
